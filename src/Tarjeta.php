<?php

namespace TrabajoTarjeta;

class Tarjeta implements Tarjeta_Interface {

    protected $saldo = 0;

    protected $viajes_plus = 2;

    protected $valor = 14.80;

    protected $costo;

    protected $id;

    protected $tipo = "Normal";

    public $caso;

    protected $costo_plus = 0.0;

    protected $tiempo;

    protected $linea_anterior = NULL;

    protected $numero_anterior = NULL;

    protected $ultimo_pago = NULL;

    protected $trasbordo = false;

    public function __construct( Tiempo_Interface $tiempo, $id ) {
        $this->tiempo = $tiempo;
        $this->id = $id;
    }
    
    /**
     * Recarga una cantidad válida de dinero a la tarjeta
     * 
     * @param float $monto
     *      La cantidad de dinero a recargar
     */
    public function recargar( $monto ) {
	  if( 10.0 == $monto  || 20.0 == $monto || 30.0 == $monto || 50.0 == $monto || 100.0 == $monto || 510.15 == $monto || 962.59 == $monto ) {
		if( 0.0 == $this->saldo ) {
			$this->saldo = $monto;
		} else {
	   		$this->saldo = $this->saldo + $monto;
		}
		return True;	    
	  } else{
	  	  return False;
	  }
    }

    /**
     * Devuelve el saldo que le queda a la tarjeta.
     *
     * @return float
     */
    public function obtener_saldo() {
      return $this->saldo;
    }
    
    /**
     * Devuelve la cantidad de viajes plus que le quedan a la tarjeta
     * 
     * @return int
     */
    public function obtener_plus() {
        return $this->viajes_plus;
    }
    
    /**
     * Usa la tarjeta para pagar un viaje en un colectivo determinado
     * 
     * @param Colectivo_Interface $colectivo
     *      El colectivo en el cual se usa la tarjeta
     * 
     * @return bool
     *      Devuelve true si se pudo pagar el viaje y false en caso contrario
     */
    public function pagar_tarjeta( $colectivo ) {
        $this->valor = 14.80;
        $this->costo_plus = 0.0;
        if ( $this->saldo < $this->valor ) {
            switch ( $this->viajes_plus ) {
                case 0:
                    return false;
                case 1:
                    $this->gastar_plus();
                    $this->costo = 0.0;
                    $this->caso = 'Viaje Plus';
                    $this->guardo_cole( $colectivo );
                    $this->trasbordo = true;
                    return true;
                case 2:
                    $this->gastar_plus();
                    $this->costo = 0.0;
                    $this->caso = 'Viaje Plus';
                    $this->guardo_cole( $colectivo );
                    $this->trasbordo = true;
                    return true;
            }
        } else {
            switch ( $this->viajes_plus ) {
                case 0:
                    $this->costo_plus = ( $this->valor ) * 2;
                    $this->costo = $this->costo_plus + $this->valor;
                    if ( $this->saldo < $this->costo ) {
                        return false;
                    } else {
                        if ( $this->hay_trans( $colectivo ) ) {
                            $this->valor = ( $this->valor / 33 ) * 10;
                            $this->costo = $this->costo_plus + $this->valor;
                            $this->saldo = $this->saldo - $this->costo;
                            $this->caso = 'Trasbordo';
                            $this->ultimo_pago = $this->tiempo->time();
                            $this->guardo_cole( $colectivo );
                            $this->trasbordo = false;
                            $this->viajes_plus = 2;
                            return true;
                        } else {
                            $this->saldo = $this->saldo - $this->costo;
                            $this->caso = 'Pagando Plus';
                            $this->ultimo_pago = $this->tiempo->time();
                            $this->guardo_cole( $colectivo );
                            $this->trasbordo = true;
                            $this->viajes_plus = 2;
                            return true;
                        }
                    }

                case 1:
                    $this->costo_plus = $this->valor;
                    $this->costo = $this->costo_plus + $this->valor;
                    if ( $this->saldo < $this->costo ) {
                        return false;
                    } else {
                        if ( $this->hay_trans( $colectivo ) ) {
                            $this->valor = ( $this->valor / 33 ) * 10;
                            $this->costo = $this->costo_plus + $this->valor;
                            $this->saldo = $this->saldo - $this->costo;
                            $this->caso = 'Trasbordo';
                            $this->ultimo_pago = $this->tiempo->time();
                            $this->guardo_cole( $colectivo );
                            $this->trasbordo = false;
                            $this->viajes_plus = 2;
                            return true;
                        } else {
                            $this->saldo = $this->saldo - $this->costo;
                            $this->caso = 'Pagando Plus';
                            $this->ultimo_pago = $this->tiempo->time();
                            $this->guardo_cole( $colectivo );
                            $this->trasbordo = true;
                            $this->viajes_plus = 2;
                            return true;
                        }
                    }
                case 2:
                    if ($this->hay_trans( $colectivo ) ) { 
                        $this->valor = ( $this->valor / 33 ) * 10;
                        $this->costo = $this->costo_plus + $this->valor;
                        $this->saldo = $this->saldo - $this->costo;
                        $this->caso = 'Trasbordo';
                        $this->ultimo_pago = $this->tiempo->time();
                        $this->guardo_cole( $colectivo );
                        $this->trasbordo = false;
                        return true;
                    } else {
                        $this->valor = 14.80;
                        $this->costo = $this->costo_plus + $this->valor;
                        $this->saldo = $this->saldo - $this->costo;
                        $this->caso = 'Normal';
                        $this->ultimo_pago = $this->tiempo->time();
                        $this->guardo_cole( $colectivo );
                        $this->trasbordo = true;
                        return true;
                    }    
            }
        }
    }
    
    /**
     * Devuelve el costo del ultimo viaje pagado con la tarjeta
     * 
     * @return float
     */
    public function obtener_costo() {
        return $this->costo;
        
    }
    
    /**
     * Reduce la cantidad de viajes plus de la tarjeta en uno
     * 
     * @return void
     */
    public function gastar_Plus() {
        $this->viajes_plus = $this->viajes_plus - 1;
    }
    
    /**
     * Devuelve el valor de pagar un viaje con la tarjeta
     * 
     * @return float
     */
    public function obtener_valor() {
        return $this->valor; 
    }

    /**
     * Devuelve el id de la tarjeta
     * 
     * @return int
     */
    public function obtener_id() {
        return $this->id;
    }

    /**
     * Devuelve el costo de pagar los viajes plus que se deben en la tarjeta
     * 
     * @return float
     */
    public function obtener_costo_plus() {
        return $this->costo_plus;
    }

    /**
     * Devuelve el tipo de la tarjeta
     * 
     * @return string
     */
    public function obtener_tipo(): string {
        return $this->tipo;
    }

    /**
     * Devuelve el tiempo en el cual se usó por última vez la tarjeta
     * 
     * @return int
     */
    public function obtener_hora() {
        return $this->ultimo_pago;
    }


    /**
     * Indica si el proximo viaje puede ser un trasbordo o no
     * 
     * @return bool
     *      Devuelve true si se puede usar trasbordo y false en caso contrario
     */
    public function hay_trans( $colectivo ) {
		return ( $this->es_trasbordo( $colectivo ) && $this->tiempo_valido() && $this->trasbordo );
    }

    /**
     * Indica si se esta usando un colectivo con linea o numero diferente al ultimo colectivo usado
     * 
     * @return bool
     *      Devuelve true si se esta usando un colectivo diferente y false en caso contrario 
     */
	public function es_trasbordo( $colectivo ) {	
		return ( ( $this->linea_anterior != $colectivo->linea() ) || ( $this->numero_anterior != $colectivo->numero() ) );
    }

    /**
     * Indica si el se excedió el tiempo para usar un trasbrodo o no
     * 
     * @return bool
     *      Devuelve true si no se excedió el tiempo y false en caso contrario
     */
    public function tiempo_valido() { 
		if ( $this->intervalo_trasbordo() ) {
			return ( $this->tiempo->time() - $this->ultimo_pago < 5400 );
		}
		return ( $this->tiempo->time() - $this->ultimo_pago < 3600 );
    }

    /**
     * Indica si se usa el intervalo mayor o menor de tiempo para el trasbordo
     * 
     * @return bool
     *      Devuelve true si se usa el intervalo mayor y false en caso contrario
     */
    public function intervalo_trasbordo() {
        $feriado = $this->tiempo->es_feriado();
		$sabado = 6 == date( 'w', $this->tiempo->time() ) && ( date( 'G', $this->tiempo->time() ) >= 14 && date( 'G', $this->tiempo->time() ) < 22);
		$domingo = 0 == date( 'w', $this->tiempo->time() ) && ( date( 'G', $this->tiempo->time() ) >= 6 && date( 'G', $this->tiempo->time() ) < 22);
		$noche = 22 == date( 'G', $this->tiempo->time() ) && date( 'G', $this->tiempo->time() ) < 6;
		return ($sabado || $domingo || $noche || $feriado);
    }

    /**
     * Guarda los valores de linea y numero del ultimo colectivo usado en los parametros linea_anterior y numero_anterior
     * 
     * @return void
     */
    public function guardo_cole ($colectivo ) {
        $this->linea_anterior = $colectivo->linea();
		$this->numero_anterior = $colectivo->numero();
    }

}

