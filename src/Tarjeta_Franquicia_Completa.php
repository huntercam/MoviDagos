<?php

namespace TrabajoTarjeta;

class Tarjeta_Franquicia_Completa extends Tarjeta {
    protected $valor = 0.0;
    protected $caso = 'Franquicia Completa';

    /**
     * Paga un viaje con la tarjeta de franquicia completa
     * 
     * @return true 
     *      Como la franquicia completa siempre es capaz de pagar un viaje la funcion solo devuelve true
     */
    public function pagarConTarjeta( ColectivoInterface $colectivo , $tiempo ) {
		$this->setUltimoPago( $tiempo );
        $this->setUltimoColectivo( $colectivo );
        return new Boleto($colectivo, $this, $valor, $caso , $tiempo );;
    }
	
}
