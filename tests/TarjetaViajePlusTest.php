<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class Tarjeta_Viaje_Plus_Test extends TestCase {

    /**
     * Comprueba que se pueden usar viajes plus.
     * 
     * @return void
     */
	 public function test_usar_viajes_plus(){
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( $tiempo_prueba, NULL );
		$colectivo = new Colectivo('mixta', '103', 420 );
		
		$boleto = $tarjeta->pagarConTarjeta( $colectivo , $tiempo_prueba );
		
        $this->assertFalse( $boleto->getTipoBoleto() == 'Saldo Insuficiente' );

	 }
	/**
     * Comprueba que no se pueden usar mas de 2 viajes plus.
     * 
     * @return void
     */
	public function test_no_usar_mas_viajes_plus(){
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( $tiempo_prueba, NULL );
		$colectivo = new Colectivo( 'mixta', '103', 420 );
		
        $tarjeta->pagarConTarjeta( $colectivo , $tiempo_prueba );
        $this->assertEquals( $tarjeta->getViajesPlus(), 1 );
		
        $tarjeta->pagarConTarjeta( $colectivo , $tiempo_prueba );
        $this->assertEquals( $tarjeta->getViajesPlus(), 0 );
		
		$boleto = $tarjeta->pagarConTarjeta( $colectivo , $tiempo_prueba );
        $this->assertEquals( $boleto->getTipoBoleto() == 'Saldo Insuficiente' , True );

	}


}
