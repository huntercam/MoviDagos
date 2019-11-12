<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class Boleto_Test extends TestCase {
	use Costos;

    /**
     * Comprueba que el valor por defecto de un boleto es igual a 14.80
     * 
     * @return void
     */
    public function test_saldo_cero() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( NULL );
        $colectivo = new Colectivo( 'mixta','103', 420 );
		
        $boleto = $tarjeta->pagarConTarjeta($colectivo , $tiempo_prueba);
        $this->assertEquals( $boleto->getTarjeta()->getCostoUltimoViaje(), 0.0  );
    }
}
