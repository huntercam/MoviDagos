<?php

namespace TrabajoTarjeta;

class Tarjeta_Franquicia_Completa implements TarjetaInterface {
    protected $valor = 0.0;
    protected $caso = 'Franquicia Completa';

    /**
     * Paga un viaje con la tarjeta de franquicia completa
     * 
     * @return true 
     *      Como la franquicia completa siempre es capaz de pagar un viaje la funcion solo devuelve true
     */
    public function pagar_tarjeta( ColectivoInterface $colectivo , $tiempo ) {
        return new Boleto($colectivo, $this, $valor, $caso , $tiempo );;
    }
	
}
