<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class Boleto_Test extends TestCase {

    /**
     * Comprueba que el valor por defecto de un boleto es igual a 14.80
     * 
     * @return void
     */
    public function test_saldo_cero() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( $tiempo_prueba, NULL );
        $colectivo = new Colectivo( 'mixta','103', 420 );
        $valor = 14.70; // 14.80
        $boleto = new Boleto( $colectivo, $tarjeta );
        $this->assertEquals( $boleto->obtener_valor(), $valor );
    }
}
