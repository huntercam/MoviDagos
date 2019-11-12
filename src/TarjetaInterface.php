<?php

namespace TrabajoTarjeta;

//require('Costos.php');





interface TarjetaInterface {

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
    public function getSaldo();

    /**
     * Devuelve la cantidad de viajes plus que le quedan a la tarjeta
     * 
     * @return int
     */
    public function getViajesPlus();
    
    /**
     * Usa la tarjeta para pagar un viaje en un colectivo determinado
     * 
     * @param Colectivo_Interface $colectivo
     *      El colectivo en el cual se usa la tarjeta
     * 
     * @return bool
     *      Devuelve true si se pudo pagar el viaje y false en caso contrario
     */
    public function pagarConTarjeta( $colectivo , $tiempo);
    
    /**
     * Reduce la cantidad de viajes plus de la tarjeta en uno
     * 
     * @return void
     */
    public function gastarPlus();

    
    /**
     * Devuelve el costo del ultimo viaje pagado con la tarjeta
     * 
     * @return float
     */
    public function getCostoUltimoViaje();


    /**
     * Devuelve el id de la tarjeta
     * 
     * @return int
     */
    public function getId();

    
    /**
     * Devuelve el tipo de la tarjeta
     * 
     * @return string
     */
    public function getTipo();

    /**
     * Indica si el proximo viaje puede ser un trasbordo o no
     * 
     * @return float 
     */
    public function hayTransbordo( $colectivo );

}
