<?php

namespace TrabajoTarjeta;


//require('BoletoInterface.php');

class Boleto implements BoletoInterface {

    protected $valor;

	protected $tarjeta;
	
	protected $colectivo;

    protected $tipoBoleto;
	
    protected $tiempo;


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
    public function __construct( ColectivoInterface $p_colectivo, TarjetaInterface $p_tarjeta, $p_valor , $p_tipoBoleto , Tiempo $p_tiempo  ) {
		$this->tarjeta = $p_tarjeta;
		$this->colectivo = $p_colectivo;
		$this->tipoBoleto = $p_tipoBoleto;
		$this->tiempo = $p_tiempo;
		$this->valor = $p_valor;
    }

    /**
     * Devuelve el valor del boleto
     * 
     * @return float
     */
    public function getValor() {
        return $this->valor;
    }


    /**
     * Devuelve el saldo restante de la tarjeta que pago por el viaje
     * 
     * @return float
     */
    public function getColectivo() {
        return $this->colectivo;
    }

    /**
     * Devuelve el tipo de la tarjeta que pago por el viaje
     * 
     * @return string
     */
    public function getTarjeta() {
        return $this->tarjeta;       
    }


    /**
     * Devuelve el tipo del boleto que fue impreso
     * 
     * @return string
     */
    public function getTipoBoleto() {
        return $this->tipoBoleto;
    } 
	
	
	/**
     * Devuelve el tiempo en que el boleto fue impreso
     * 
     * @return string
     */
    public function getTiempo() {
        return $this->tiempo;
    } 


}
