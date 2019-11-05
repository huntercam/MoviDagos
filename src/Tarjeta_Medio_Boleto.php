<?php

namespace TrabajoTarjeta;


class Tarjeta_Medio_Boleto extends Tarjeta {

use Pagos;

    protected $tipo = 'Medio';

    protected $tiempo_de_espera = 300;

    protected $valor;

    protected $ultimopago = null;


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
        $this->valor = $this->getCostoViaje();
        
        if ( $this->tiempo_de_espera_cumplido() ) { /// tiempo para usar medio boleto 
		    $this->valor = $this->getCostoMedioBoleto();
        }
        
        /// $this->viajes_plus te da la cantidad de viajes plus disponibles
        
        $this->trasbordo = false;
        
        if( $this->hay_trans ( $colectivo ) ){
		   $this->trasbordo = true;
		   $this->valor = $this->getCostoTransbordo();
        }
        $this->valor = $this->valor +  ( (2 - $this->viajes_plus ) * $this->getCostoViaje() );

        if ( $this->saldo < $this->valor ) { /// si no te alcanza la plata
            switch ( $this->viajes_plus ) {
                case 0:
                    return false;
                case 1:
                    $this->gastar_plus();
                    $this->costo = 0.0;
                    $this->caso = 'Viaje Plus';
                    $this->guardo_cole( $colectivo );
                    return true;
                case 2:
                    $this->gastar_plus();
                    $this->costo = 0.0;
                    $this->caso = 'Viaje Plus';
                    $this->guardo_cole( $colectivo );
                    return true;
            }
        } else { /// lo pagas
            $this->saldo = $this->saldo - $this->costo;
	    $this->ultimo_pago = $this->tiempo->time();
            
            	$this->caso = 'Normal';
            $this->costo = $this->valor;
            if($this->trasbordo){
            	$this->caso = 'Trasbordo';
            }
            if($this->viajes_plus != 2){
            	$this->caso = 'Pagando Plus';
            }
            
            $this->ultimo_pago = $this->tiempo->time();
            $this->guardo_cole( $colectivo );
            $this->viajes_plus = 2;
            return true;
        
        }
    
    }

    /**
     * Indica si se alcanzó o no el tiempo de espera necesario para usar el medio boleto
     * 
     * @return bool
     *      Devuelve true si se alcanzó el tiempo y false en caso contrario
     */
    public function tiempo_de_espera_cumplido() {
        $ultimo_pago = $this->obtener_ultima_fecha_pagada();
        $fecha_actual = $this->tiempo->time();
        $diferencia_fechas = $fecha_actual - $ultimo_pago;
        if( $diferencia_fechas >= $this->obtener_tiempo_de_espera() ) {
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
    public function obtener_tiempo_de_espera() {
        return $this->tiempo_de_espera;
    }

    /**
     * Devuelve el tiempo que pasó desde la última vez que fue usada la tarjeta
     * 
     * @return int
     */
    public function obtener_ultima_fecha_pagada() {
        return $this->ultimo_pago;
    }
}

