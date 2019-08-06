<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class TarjetaTest extends TestCase {

    /**
     * Comprueba que la tarjeta aumenta su saldo cuando se carga saldo válido.
     * 
     * @return void
     */
    public function test_carga_saldo() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( $tiempo_prueba, NULL );

        $this->assertTrue( $tarjeta->recargar( 10 ) );
        $this->assertEquals( $tarjeta->obtener_saldo(), 10 );

        $this->assertTrue( $tarjeta->recargar( 20 ) );
        $this->assertEquals($tarjeta->obtener_saldo(), 30);

        $this->assertTrue( $tarjeta->recargar( 30 ) );
        $this->assertEquals( $tarjeta->obtener_saldo(), 60 );

        $this->assertTrue( $tarjeta->recargar( 50 ) );
        $this->assertEquals( $tarjeta->obtener_saldo(), 110 );

        $this->assertTrue( $tarjeta->recargar( 100 ) );
        $this->assertEquals( $tarjeta->obtener_saldo(), 210 );

        $this->assertTrue( $tarjeta->recargar( 510.15 ) );
        $this->assertEquals( $tarjeta->obtener_saldo(), 720.15 );

        $this->assertTrue( $tarjeta->recargar( 962.59 ) );
        $this->assertEquals( $tarjeta->obtener_saldo(), 1682.74 );
    }

    /**
     * Comprueba que la tarjeta no puede cargar saldos invalidos.
     * 
     * @return void
     */
    public function test_carga_saldo_invalido() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( $tiempo_prueba, NULL );

      $this->assertFalse( $tarjeta->recargar( 15 ) );
      $this->assertEquals( $tarjeta->obtener_saldo(), 0 );
    }
}
