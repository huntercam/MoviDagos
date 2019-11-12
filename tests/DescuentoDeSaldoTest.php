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
        $cole= new Colectivo( 'mixta','103',420 );        
        $tarjeta->recargar( 100.0 );
        $tarjeta->gastar_plus();
        
        $this->assertEquals( $cole->pagar_con( $tarjeta ), $boleto = new Boleto( $cole, $tarjeta ) );
        $this->assertEquals( $tarjeta->obtener_saldo(), ( 100.0-29.60 ) );
    }

    /**
     * Comprueba que se descuenten dos viajes plus del saldo.
     * 
     * @return void
     */
    public function test_descuento_de_dos_plus() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta(  NULL );
        $cole= new Colectivo( 'mixta', '103', 420 );
        $tarjeta->recargar( 100.0 );
        $tarjeta->gastar_plus();
        $tarjeta->gastar_plus();
        $this->assertEquals( $cole->pagar_con( $tarjeta ), $boleto = new Boleto( $cole,$tarjeta ) );
        $this->assertEquals( $tarjeta->obtener_saldo(), ( 100.0-44.40 ) );
    }
}