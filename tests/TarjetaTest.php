<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class TarjetaTest extends TestCase {
use Costos;
    /**
     * Comprueba que la tarjeta aumenta su saldo cuando se carga saldo válido.
     * 
     * @return void
     */
    public function test_carga_saldo() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( NULL );

        $this->assertTrue( $tarjeta->recargar( 10 ) );
        $this->assertEquals( $tarjeta->getSaldo(), 10 );

        $this->assertTrue( $tarjeta->recargar( 20 ) );
        $this->assertEquals($tarjeta->getSaldo(), 30);

        $this->assertTrue( $tarjeta->recargar( 30 ) );
        $this->assertEquals( $tarjeta->getSaldo(), 60 );

        $this->assertTrue( $tarjeta->recargar( 50 ) );
        $this->assertEquals( $tarjeta->getSaldo(), 110 );

        $this->assertTrue( $tarjeta->recargar( 100 ) );
        $this->assertEquals( $tarjeta->getSaldo(), 210 );

        $this->assertTrue( $tarjeta->recargar( $this->getMontoPromo1() + 10 ) );
        $this->assertEquals( $tarjeta->getSaldo(), 210 + $this->getMontoPromo1() + 10 + $this->getMontoAdicionalPromo1() );
		
		$saldo = 210 + $this->getMontoPromo1() + 10 + $this->getMontoAdicionalPromo1();

        $this->assertTrue( $tarjeta->recargar( $this->getMontoPromo2() + 10 ) );
        $this->assertEquals( $tarjeta->getSaldo(), $saldo + $this->getMontoPromo2() + 10 + $this->getMontoAdicionalPromo2()  );
    }

    /**
     * Comprueba que la tarjeta no puede cargar saldos invalidos. **¿Porque no podría?**
     * 
     * @return void
     */
    public function test_carga_saldo_invalido() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( NULL );
		
		$tarjeta->recargar( 15 );
      $this->assertEquals( $tarjeta->getSaldo(), 15 );
    }
}
