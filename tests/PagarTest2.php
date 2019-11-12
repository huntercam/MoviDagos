<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class PagarTest2 extends TestCase {

    /**
     * Comprueba que la tarjeta pueda recargarse
     * 
     * @return void
     */
    public function test_pagar_monto_consaldo() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( NULL );
        $colectivo = new Colectivo( 'mixta', '103', 420 );
        $tarjeta->recargar( 50.0 );
        $this->assertEquals( $tarjeta->getSaldo() , 50.0 );
    }

    /**
     * Comprueba que la tarjeta puede pagar sin saldo hasta dos veces.
     * 
     * @return void
     */
    public function test_pagar_monto_sin_saldo() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( NULL );
		$colectivo = new Colectivo( 'mixta', '103', 420 );
		
        $boleto = $tarjeta->pagarConTarjeta( $colectivo , $tiempo_prueba );
        $this->assertEquals( $tarjeta->getViajesPlus(), 1 );
        $this->assertEquals( $tarjeta->getSaldo(), 0.0 );
		
        $boleto = $tarjeta->pagarConTarjeta( $colectivo , $tiempo_prueba );
        $this->assertEquals( $tarjeta->getViajesPlus(), 0 );
        $this->assertEquals( $tarjeta->getSaldo(), 0.0 );
    }    
}
