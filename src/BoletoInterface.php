<?php

namespace TrabajoTarjeta;

interface BoletoInterface {

    /**
     * Devuelve el valor del boleto.
     *
     * @return float
     */
    public function getValor();

    /**
     * Devuelve el colectivo que imprimió el boleto
     * 
     * @return string
     */
    public function getColectivo();

    /**
     * Devuelve la tarjeta que pago por el viaje
     * 
     * @return int
     */
    public function getTarjeta();


    /**
     * Devuelve el tipo del boleto que fue impreso
     * 
     * @return string
     */
    public function getTipoBoleto();
	
	/**
     * Devuelve el tiempo en que el boleto fue impreso
     * 
     * @return int
     */
    public function getTiempo();
    


}
