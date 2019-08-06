<?php

namespace TrabajoTarjeta;

class Tarjeta_Medio_Boleto_Universitario extends Tarjeta {

    protected $valor = 14.80;

    protected $ultimo_pago;

    public $tipo = 'Medio Universitario';

    protected $cantidad_pagos = 0;

    protected $tiempo_de_espera = 300;
    
    /**
     * Devuelve el valor que se imprimirá en el boleto
     * 
     * @return float
     */
    public function obtener_valor_boleto() {
        if ( $this->medio_disponible() ) {
            return ( $this->valor ) / 2;
        } else {
            return $this->valor;
        }
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
        $valor_aux = $this->obtener_valor_boleto();
        if ( $this->saldo < $valor_aux ) {
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
                    $this->costo_plus = 14.80 + 14.80;
                    $this->costo = $valor_aux + $this->costo_plus;
                    if ( $this->saldo < $this->costo ) {
                        return false;
                    } else {
                        if ( $this->hay_trans( $colectivo ) ) {
                            $valor_aux = ( $valor_aux *33 ) / 100;
                            $this->costo = $this->costo_plus + $valor_aux;
                            $this->saldo = $this->saldo - $this->costo;
                            $this->caso = 'Trasbordo';
                            $this->ultimo_pago = $this->tiempo->time();
                            $this->guardo_cole( $colectivo );
                            $this->trasbordo = false;
                            if ( $this->cantidad_pagos < 2)  {
                                $this->cantidad_pagos = $this->cantidad_pagos + 1;
                            }
                            $this->viajes_plus = 2;
                            return true;
                        } else {
                            $this->saldo = $this->saldo - $this->costo;
                            $this->caso = 'Pagando Plus';
                            $this->ultimo_pago = $this->tiempo->time();
                            $this->trasbordo = true;
                            $this->guardo_cole( $colectivo );
                            if ($this->cantidad_pagos < 2){
                                $this->cantidad_pagos = $this->cantidad_pagos + 1;
                            }
                            return true;
                            $this->viajes_plus = 2;
                        }
                    }

                case 1:
                    $this->costo_plus = 14.80;
                    if ( $this->saldo < $this->costo ) {
                        return false;
                    } else {
                        if ( $this->hay_trans( $colectivo ) ) {
                            $valor_aux = ( $valor_aux * 33 ) / 100;
                            $this->costo = $this->costo_plus + $valor_aux;
                            $this->saldo = $this->saldo - $this->costo;
                            $this->caso = 'Trasbordo';
                            $this->ultimo_pago = $this->tiempo->time();
                            $this->guardo_cole( $colectivo );
                            $this->trasbordo = false;
                            if ( $this->cantidad_pagos < 2 ) {
                                $this->cantidad_pagos = $this->cantidad_pagos + 1;
                            }
                            $this->viajes_plus = 2;
                            return true;
                        } else {
                            $this->costo = $this->costo_plus + $valor_aux;
                            $this->saldo = $this->saldo - $this->costo;
                            $this->caso = 'Pagando Plus';
                            $this->ultimo_pago = $this->tiempo->time();
                            $this->trasbordo = true;
                            $this->guardo_cole( $colectivo );
                            if ($this->cantidad_pagos < 2){
                                $this->cantidad_pagos = $this->cantidad_pagos + 1;
                            }
                            $this->viajes_plus = 2;
                            return true;
                        }
                    }
                case 2:
                    if ( $this->hay_trans( $colectivo ) ) { 
                        $valor_aux = ($valor_aux * 33 ) / 100;
                        $this->costo = $this->costo_plus + $valor_aux;
                        $this->saldo = $this->saldo - $this->costo;
                        $this->caso = 'Trasbordo';
                        $this->ultimo_pago = $this->tiempo->time();
                        $this->guardo_cole( $colectivo );
                        $this->trasbordo = false;
                        if ($this->cantidad_pagos < 2) {
                            $this->cantidad_pagos = $this->cantidad_pagos + 1;
                        }
                        return true;
                    } else {
                        $this->costo = $this->costo_plus + $valor_aux;
                        $this->saldo = $this->saldo - $this->costo;
                        $this->caso = 'Medio Universitario';
                        $this->ultimo_pago = $this->tiempo->time();
                        $this->guardo_cole( $colectivo );
                        $this->trasbordo = true;
                        if ( $this->cantidad_pagos < 2 ) {
                            $this->cantidad_pagos = $this->cantidad_pagos + 1;
                        }
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
        $ultimo_pago = $this->ultimo_pago;
        $fecha_actual = $this->tiempo->time();
        $diferencia_fechas = $fecha_actual - $ultimo_pago;
        if($diferencia_fechas >= $this->obtener_tiempo_de_espera()){
                return TRUE;
        }
        return FALSE;
    }


    /**
     * Indica si el medio boleto está habilitado para ser usado o no
     * 
     * @return bool
     *      Devuelve true si se puede usar el medio boleto y false en caso contrario
     */
    public function medio_disponible() {
        if($this->cantidad_pagos < 2 ) {
            if( $this->tiempo_de_espera_cumplido() ) {
                return True;
            }    
        }
        if( $this->tiempo_de_espera_ultimo_medio_cumplido() ) {
          $this->cantidad_pagos = 0;
          return TRUE;
        }
        return FALSE;
    }

    /**
     * Indica si pasó la tarjeta fue usada por última vez el día actual o no
     * 
     * @return bool
     *      Devuelve true si la tarjeta fue usada un día anterior al actual y false en caso contrario
     */
    public function tiempo_de_espera_ultimo_medio_cumplido() {
        $fecha_ultima = $this->obtener_ultima_fecha_pagada();
        $fecha_ultima = date( "d/m/y", $fecha_ultima );
        $fecha_actual = $this->tiempo->time();
        $fecha_actual = date( "d/m/y", $fecha_actual );
        if($fecha_ultima < $fecha_actual){
             return TRUE;
        }
        return FALSE;
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

