<?php

namespace TrabajoTarjeta;

interface Boleto_Interface {

    /**
     * Devuelve el valor del boleto.
     *
     * @return float
     */
    public function obtener_valor();

    /**
     * Devuelve la linea del colectivo que imprimió el boleto
     * 
     * @return string
     */
    public function obtener_linea();

    /**
     * Devuelve el id de la tarjeta que pago por el viaje
     * 
     * @return int
     */
    public function obtener_tarjeta_id();

    /**
     * Devuelve el saldo restante de la tarjeta que pago por el viaje
     * 
     * @return float
     */
    public function obtener_saldo();

    /**
     * Devuelve el tipo de la tarjeta que pago por el viaje
     * 
     * @return string
     */
    public function obtener_tipo_tarjeta();

    /**
     * Devuelve el costo total del viaje
     * 
     * @return float
     */
    public function obtener_costo_total();

    /**
     * Devuelve el tipo del boleto que fue impreso
     * 
     * @return string
     */
    public function obtener_tipo_boleto();
    
    /**
     * Devuelve el costo de pagar los viajes plus que se debian, si se debía ningun viaje plus devolverá 0
     * 
     * @return float
     */
    public function obtener_costo_plus();

    /**
     * Devuelve la cantidad de viajes plus restantes en la tarjeta que pagó por el viaje
     * 
     * @return int
     */
    public function obtener_cantidad_viajes_plus();
}
