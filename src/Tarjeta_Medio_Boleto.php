<?php

namespace TrabajoTarjeta;


class Tarjeta_Medio_Boleto extends Tarjeta {

use Costos;


    protected $tiempoEsperaMedio = 300;

    /**
     * Usa la tarjeta para pagar un viaje en un colectivo determinado
     * 
     * @param Colectivo_Interface $colectivo
     *      El colectivo en el cual se usa la tarjeta
     * 
     * @return bool
     *      Devuelve true si se pudo pagar el viaje y false en caso contrario
     */

    public function pagarConTarjeta( ColectivoInterface $colectivo , $tiempo ) {
        $valor = $this->getCostoViaje();
        
		$caso = 'Normal';
		
		if( $this->tiempoEsperaMedioCumplido($tiempo) ){
		   $caso = 'Medio';
		   $valor = $this->getCostoMedioBoleto();
        }
		
		
        if( $this->hayTransbordo( $colectivo , $tiempo ) ){
		   $caso = 'Transbordo';
		   $valor = $this->getCostoTransbordo();
        }
		
        $valor +=  ( (2 - $this->getViajesPlus() ) * $this->getCostoViaje() );

        if ( $valor > $this->getSaldo() ) { /// si no te alcanza la plata
			
            switch ( $this->getViajesPlus() ) {
                case 0:
                    return new Boleto($colectivo, $this, $valor,  'Saldo Insuficiente' , $tiempo );
                case 1:
                    $this->gastarPlus();
                    $this->costo = 0.0;
					$this->setUltimoPago( $tiempo );
                    $this->setUltimoColectivo( $colectivo );
                    return new Boleto($colectivo, $this, $this->costo, 'Viaje Plus' , $tiempo );
                case 2:
                    $this->gastarPlus();
                    $this->costo = 0.0;
					$this->setUltimoPago( $tiempo );
                    $this->setUltimoColectivo( $colectivo );
                    return new Boleto($colectivo, $this, $this->costo, 'Viaje Plus' , $tiempo );
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
            return new Boleto($colectivo, $this, $valor, $caso , $tiempo );
        
        }
    }

    /**
     * Indica si se alcanzó o no el tiempo de espera necesario para usar el medio boleto
     * 
     * @return bool
     *      Devuelve true si se alcanzó el tiempo y false en caso contrario
     */
    public function tiempoEsperaMedioCumplido($tiempo) {
        $ultimo_pago = $this->getUltimoPago();
		
        $fecha_actual = $tiempo->getTiempo();
		
        $diferencia_fechas = $fecha_actual - $ultimo_pago;
        if( $diferencia_fechas >= $this->getTiempoEsperaMedio() ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Devuelve el tiempo que se debe esperar después de usar el medio boleto para poder volver a usarlo en segundos
     * 
     * @return int
     */
    public function getTiempoEsperaMedio() {
        return $this->tiempoEsperaMedio;
    }

}

