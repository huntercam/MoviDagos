<?php
namespace TrabajoTarjeta;
use PHPUnit\Framework\TestCase;
class DescuentodesaldoTest extends TestCase {
	use Costos;
    /**
     * Comprueba que se descuente un viaje plus del saldo.
     * 
     * @return void
     */
     
    public function test_descuento_de_un_plus() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( NULL );
        $colectivo = new Colectivo( 'mixta','103',420 );        
        $tarjeta->recargar( 100.0 );
        $tarjeta->gastarPlus();
		$boleto = $tarjeta->pagarConTarjeta( $colectivo , $tiempo_prueba );
        $this->assertEquals( $boleto->getValor() , ( 100.0 -  ( 2 * $this->getCostoViaje() ) ) );
    }

    /**
     * Comprueba que se descuenten dos viajes plus del saldo.
     * 
     * @return void
     */
    public function test_descuento_de_dos_plus() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta(  NULL );
        $colectivo = new Colectivo( 'mixta', '103', 420 );
        $tarjeta->recargar( 100.0 );
        $tarjeta->gastarPlus();
        $tarjeta->gastarPlus();
		$boleto = $tarjeta->pagarConTarjeta( $colectivo , $tiempo_prueba );
        $this->assertEquals( $boleto->getValor(), ( 100.0 -  ( 3 * $this->getCostoViaje() ) ) );
    }
}