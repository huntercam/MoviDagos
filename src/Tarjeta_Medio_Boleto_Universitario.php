<?php

namespace TrabajoTarjeta;

class Tarjeta_Medio_Boleto_Universitario extends Tarjeta_Medio_Boleto {
use Costos;

    public $tipo = 'Medio Universitario';
	
	protected $penultimoPago = NULL;

    

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
		
		if( $this->tiempoEsperaMedioCumplido($tiempo) && $tiempo->diferencia($penultimoPago) > 60*60*24 ){
		   $caso = 'Medio Boleto Universitario';
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
	
	public function setUltimoPago ( $tiempo ) {
		$this->penultimoPago = $this->ultimoPago;
        $this->ultimoPago = $tiempo->getTiempo();
    }
    
	

}

