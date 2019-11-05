<?php

namespace TrabajoTarjeta;
include 'Costos.php';



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
        $this->valor = getCostoViaje();
        if ( $this->tiempo_de_espera_cumplido() ) {
		    $this->valor = $this->valor / 2; 	
        }

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
                    $this->costo_plus = getCostoViaje() + getCostoViaje();
                    if ( $this->saldo < $this->costo ) {
                        return false;
                    } else {
                        if ( $this->hay_trans ( $colectivo ) ) {
                            $this->valor = ( $this->valor * 33 ) / 100;
                            $this->costo = $this->costo_plus + $this->valor;
                            $this->saldo = $this->saldo - $this->costo;
                            $this->caso = 'Trasbordo';
                            $this->ultimo_pago = $this->tiempo->time();
                            $this->guardo_cole( $colectivo );
                            $this->trasbordo = false;
                            $this->viajes_plus = 2;
                            return true;
                        } else {
                            $this->costo = $this->costo_plus + $this->valor;
                            $this->saldo = $this->saldo - $this->costo;
                            $this->caso = 'Pagando Plus';
                            $this->ultimo_pago = $this->tiempo->time();
                            $thvaloris->guardo_cole( $colectivo );
                            $this->trasbordo = true;
                            $this->viajes_plus = 2;
                            return true;
                        }
                    }

                case 1:
                    $this->costo_plus = getCostoViaje();
                    if ( $this->saldo < $this->costo ) {
                        return false;
                    } else {
                        if ( $this->hay_trans( $colectivo ) ) {
                            $this->valor = ( $this->valor * 33 ) / 100;
                            $this->costo = $this->costo_plus + $this->valor;
                            $this->saldo = $this->saldo - $this->costo;
                            $this->caso = 'Trasbordo';
                            $this->ultimo_pago = $this->tiempo->time();
                            $this->guardo_cole ($colectivo );
                            $this->trasbordo = false;
                            $this->viajes_plus = 2;
                            return true;
                        } else {
                            $this->costo = $this->costo_plus + $this->valor;
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
                    if( $this->hay_trans( $colectivo ) ) { 
                        $this->valor = ( $this->valor * 33 ) / 100;
                        $this->costo = $this->costo_plus + $this->valor;
                        $this->saldo = $this->saldo - $this->costo;
                        $this->caso = 'Trasbordo';
                        $this->ultimo_pago = $this->tiempo->time();
                        $this->guardo_cole( $colectivo );
                        $this->trasbordo = false;
                        return true;
                    } else{
                        $this->costo = $this->valor;
                        $this->saldo = $this->saldo - $this->costo;
                        $this->caso = 'Medio';
                        $this->ultimo_pago = $this->tiempo->time();
                        $this->guardo_cole( $colectivo );
                        $this->trasbordo = true;
                        return true;
                    }    
            }
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
