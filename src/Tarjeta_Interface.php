<?php

namespace TrabajoTarjeta;

interface Tarjeta_Interface {

    /**
     * Recarga una tarjeta con un cierto valor de dinero.
     *
     * @param float $monto
     *
     * @return bool
     *   Devuelve TRUE si el monto a cargar es válido, o FALSE en caso de que no
     *   sea valido.
     */
    public function recargar( $monto );

    /**
     * Devuelve el saldo que le queda a la tarjeta.
     *
     * @return float
     */
    public function obtener_saldo();

    /**
     * Devuelve la cantidad de viajes plus que le quedan a la tarjeta
     * 
     * @return int
     */
    public function obtener_plus();
    
    /**
     * Usa la tarjeta para pagar un viaje en un colectivo determinado
     * 
     * @param Colectivo_Interface $colectivo
     *      El colectivo en el cual se usa la tarjeta
     * 
     * @return bool
     *      Devuelve true si se pudo pagar el viaje y false en caso contrario
     */
    public function pagar_tarjeta( $colectivo );
    
    /**
     * Reduce la cantidad de viajes plus de la tarjeta en uno
     * 
     * @return void
     */
    public function gastar_plus();

    /**
     * Devuelve el valor de pagar un viaje con la tarjeta
     * 
     * @return float
     */
    public function obtener_valor();
    
    /**
     * Devuelve el costo del ultimo viaje pagado con la tarjeta
     * 
     * @return float
     */
    public function obtener_costo();


    /**
     * Devuelve el id de la tarjeta
     * 
     * @return int
     */
    public function obtener_id();

    /**
     * Devuelve el costo de pagar los viajes plus que se deben en la tarjeta
     * 
     * @return float
     */
    public function obtener_costo_plus();
    
    /**
     * Devuelve el tipo de la tarjeta
     * 
     * @return string
     */
    public function obtener_tipo();

    /**
     * Indica si el proximo viaje puede ser un trasbordo o no
     * 
     * @return float 
     */
    public function es_trasbordo( $colectivo );

}
