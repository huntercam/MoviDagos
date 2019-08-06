<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class PagarTest2 extends TestCase {

    /**
     * Comprueba que la tarjeta pueda pagar con saldo.
     * 
     * @return void
     */
    public function test_pagar_monto_consaldo() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( $tiempo_prueba, NULL );
        $colectivo = new Colectivo( 'mixta', '103', 420 );
        $tarjeta->recargar( 50.0 );
        $this->assertEquals( $tarjeta->obtener_saldo(), 35.2 );
    }

    /**
     * Comprueba que la tarjeta puede pagar sin saldo hasta dos veces.
     * 
     * @return void
     */
    public function test_pagar_monto_sin_saldo() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( $tiempo_prueba, NULL );
		$colectivo = new Colectivo( 'mixta', '103', 420 );
        $boleto = $colectivo->pagar_con( $tarjeta );
        $this->assertEquals( $tarjeta->obetener_plus(), 1 );
        $this->assertEquals( $tarjeta->obtener_saldo(), 0.0 );
        $boleto = $colectivo->pagar_con( $tarjeta );
        $this->assertEquals( $tarjeta->obtener_plus(), 0 );
        $this->assertEquals( $tarjeta->obtener_saldo(), 0.0 );
    }    
}
