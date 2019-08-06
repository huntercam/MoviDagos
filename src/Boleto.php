<?php

namespace TrabajoTarjeta;

class Boleto implements Boleto_Interface {

    protected $valor;

    protected $id;

    protected $tipo_tarjeta;

    protected $costo_total;

    protected $linea;

    protected $saldo;

    protected $cantidad_viajes_plus;

    protected $tipo_boleto;

    protected $costo_plus;

    /**
     * Constructor de la clase que asigna los valores a las propiedades del objeto cuando se imprime un boleto
     * 
     * @param Colectivo_Interface $colectivo
     *      El colectivo que imprimió el boleto
     * @param Tarjeta_Interface $tarjeta
     *      La tarjeta que pagó por el boleto
     * 
     * @return void
     */
    public function __construct( $colectivo, $tarjeta ) {
        $this->valor = $tarjeta->obtener_valor();
        $this->hora = $tarjeta->obtener_hora();
        $this->id = $tarjeta->obtener_id();
        $this->tipoTarjeta = $tarjeta->obtener_tipo();
        $this->linea = $colectivo->linea();
        $this->saldo = $tarjeta->obtener_saldo();
        $this->cantidad_viajes_plus = $tarjeta->obtener_plus();
        $this->costoTotal = $tarjeta->obtener_costo();
        $this->tipoBoleto = $tarjeta->caso;
        $this->costoplus = $tarjeta->obtener_costo_plus();
    }

    /**
     * Devuelve el valor del boleto
     * 
     * @return float
     */
    public function obtener_valor() {
        return $this->valor;
    }

    /**
     * Devuelve la linea del colectivo que imprimió el boleto
     * 
     * @return string
     */
    public function obtener_linea() {
        return $this->linea;
    }


    /**
     * Devuelve el id de la tarjeta que pago por el viaje
     * 
     * @return int
     */
    public function obtener_tarjeta_id() {
        return $this->id;
    }

    /**
     * Devuelve el saldo restante de la tarjeta que pago por el viaje
     * 
     * @return float
     */
    public function obtener_saldo() {
        return $this->saldo;
    }

    /**
     * Devuelve el tipo de la tarjeta que pago por el viaje
     * 
     * @return string
     */
    public function obtener_tipo_tarjeta() {
        return $this->tipoTarjeta;       
    }

    /**
     * ???
     */
    public function obtener_hora() {
        return $this->hora;
    }

    /**
     * Devuelve el costo total del viaje
     * 
     * @return float
     */
    public function obtener_costo_total() {
        return $this->costoTotal;
    }

    /**
     * Devuelve el tipo del boleto que fue impreso
     * 
     * @return string
     */
    public function obtener_tipo_boleto() {
        return $this->tipoBoleto;
    } 

    /**
     * Devuelve el costo de pagar los viajes plus que se debian, si se debía ningun viaje plus devolverá 0
     * 
     * @return float
     */
    public function obtener_costo_plus() {
        return $this->costoplus;
    }

    /**
     * Devuelve la cantidad de viajes plus restantes en la tarjeta que pagó por el viaje
     * 
     * @return int
     */
    public function obtener_cantidad_viajes_plus() {
        return $this->cantidad_viajes_plus;
    }

}
