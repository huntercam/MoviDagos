<?php

namespace TrabajoTarjeta;
//require('TarjetaInterface.php');


class Tarjeta implements TarjetaInterface {

	use Costos;
	
	protected $id;
	
    protected $saldo = 0;

    protected $viajesPlus = 2;

    protected $costo;

    protected $lineaAnterior = NULL;

    protected $numeroAnterior = NULL;

    protected $ultimoPago = NULL;


    public function __construct( $id ) {
        $this->id = $id;
    }
    
    /**
     * Recarga una cantidad válida de dinero a la tarjeta
     * 
     * @param float $monto
     *      La cantidad de dinero a recargar
     */
    public function recargar( $monto ) {
	  if( $monto == $this->getMontoPromo1() ) {
			$this->saldo +=  $monto + $this->getMontoAdicionalPromo1() ;
		return True;	    
	  }
	  if( $monto == $this->getMontoPromo2()) {
			$this->saldo +=  $monto + $this->getMontoAdicionalPromo2();
		return True;	    
	  }
		$this->saldo +=  $monto;
		return True;
    }

    /**
     * Devuelve el saldo que le queda a la tarjeta.
     *
     * @return float
     */
    public function getSaldo() {
      return $this->saldo;
    }
    
    /**
     * Devuelve la cantidad de viajes plus que le quedan a la tarjeta
     * 
     * @return int
     */
    public function getViajesPlus() {
        return $this->viajesPlus;
    }
    
    /**
     * Usa la tarjeta para pagar un viaje en un colectivo determinado
     * 
     * @param Colectivo_Interface $colectivo
     *      El colectivo en el cual se usa la tarjeta
	 
	 @param $tiempo
     *      El tiempo en el cual se ejecuta el pago
     * 
     * @return Boleto
     *      Devuelve un boleto con el viaje efectuado
     */
    public function pagarConTarjeta( $colectivo , $tiempo ) {
        $valor = $this->getCostoViaje();
        
        $this->trasbordo = false;
        
		$caso = '';
        if( $this->hayTransbordo( $colectivo ) ){
		   $caso = 'Transbordo';
		   $valor = $this->getCostoTransbordo();
        }
        $valor +=  ( (2 - $this->getViajesPlus() ) * $this->getCostoViaje() );

        if ( $valor > $this->getSaldo() ) { /// si no te alcanza la plata
			
            switch ( $this->getViajesPlus() ) {
                case 0:
                    return new Boleto($colectivo, $this, 'Saldo Insuficiente' , $tiempo );
                case 1:
                    $this->gastarPlus();
                    $this->costo = 0.0;
					$this->setUltimoPago( $tiempo );
                    $this->setUltimoColectivo( $colectivo );
                    return new Boleto($colectivo, $this, 'Viaje Plus' , $tiempo );
                case 2:
                    $this->gastarPlus();
                    $this->costo = 0.0;
					$this->setUltimoPago( $tiempo );
                    $this->setUltimoColectivo( $colectivo );
                    return new Boleto($colectivo, $this, 'Viaje Plus' , $tiempo );
            }
        } else { /// lo pagas

            $this->costo = $valor;
            $this->saldo -= $this->costo;
            

            if($this->getViajesPlus() != 2){
            	$caso = 'Pagando Plus';
            }
            
			$this->viajesPlus = 2;
            $this->setUltimoPago( $tiempo );
            $this->setUltimoColectivo( $colectivo );
            return new Boleto($colectivo, $this, $caso , $tiempo );
        
        }
    }
    
    /**
     * Devuelve el costo del ultimo viaje pagado con la tarjeta
     * 
     * @return float
     */
    public function getCostoUltimoViaje() {
        return $this->costo;
        
    }
    
    /**
     * Reduce la cantidad de viajes plus de la tarjeta en uno
     * 
     * @return void
     */
    public function gastarPlus() {
        $this->viajesPlus -=  1;
    }
    

    /**
     * Devuelve el id de la tarjeta
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }


    /**
     * Devuelve el tipo de la tarjeta
     * 
     * @return string
     */
    public function getTipo(): string {
        return $this->tipo;
    }

    /**
     * Devuelve el tiempo en el cual se usó por última vez la tarjeta
     * 
     * @return int
     */
    public function getUltimoUso() {
        return $this->ultimoPago;
    }


    /**
     * Indica si el proximo viaje puede ser un trasbordo o no
     * 
     * @return bool
     *      Devuelve true si se puede usar trasbordo y false en caso contrario
     */
    public function hayTransbordo( $colectivo ) {
		return ( $this->EsOtroColectivo( $colectivo ) && $this->tiempoTransbordoTranscurrido() );
    }

    /**
     * Indica si se esta usando un colectivo con linea o numero diferente al ultimo colectivo usado
     * 
     * @return bool
     *      Devuelve true si se esta usando un colectivo diferente y false en caso contrario 
     */
	public function EsOtroColectivo( $colectivo ) {	
		return ( ( $this->lineaAnterior != $colectivo->linea() ) || ( $this->numeroAnterior != $colectivo->numero() ) );
    }

    /**
     * Indica si el se excedió el tiempo para usar un trasbrodo o no
     * 
     * @return bool
     *      Devuelve true si no se excedió el tiempo y false en caso contrario
     */
    public function tiempoTransbordoTranscurrido() { 
		if ( $this->trasbordoEspecial() ) {
			return ( $this->tiempo - $this->ultimo_pago < 5400 ); /// hora  y media
		}
		return ( $this->tiempo - $this->ultimo_pago < 3600 ); /// hora
    }

    /**
     * Indica si se usa el intervalo mayor o menor de tiempo para el trasbordo
     * 
     * @return bool
     *      Devuelve true si se usa el intervalo mayor y false en caso contrario
     */
    public function trasbordoEspecial() {
        $feriado = $this->tiempo->feriado();
		$sabado = $this->tiempo->diaDeSemana() == 6 &&  $this->tiempo->dentroDeHoras(14,22);
		$domingo = $this->tiempo->diaDeSemana() == 0 && $this->tiempo->dentroDeHoras(6,22);
		$noche = $this->tiempo->dentroDeHoras(22,24) || $this->tiempo->dentroDeHoras(0,6); 
		return ($sabado || $domingo || $noche || $feriado);
    }

    /**
     * Guarda los valores de linea y numero del ultimo colectivo usado en los parametros linea_anterior y numero_anterior
     * 
     * @return void
     */
    public function setUltimoColectivo ( $colectivo ) {
        $this->lineaAnterior = $colectivo->linea();
		$this->numeroAnterior = $colectivo->numero();
    }
	
	public function setUltimoPago ( $tiempo ) {
        $this->ultimoPago = $tiempo;
    }

}

