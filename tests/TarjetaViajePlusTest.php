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
        $this->assertEquals($colectivo->pagar_con( $tarjeta ), new Boleto( $colectivo, $tarjeta ) );
        $this->assertEquals( $colectivo->pagar_con( $tarjeta ), new Boleto( $colectivo, $tarjeta ) );

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
        $colectivo->pagar_con( $tarjeta );
        $this->assertEquals( $tarjeta->obtener_plus(), 1 );
        $colectivo->pagar_con( $tarjeta );
        $this->assertEquals( $tarjeta->obtener_plus(), 0 );
        $this->assertEquals( $colectivo->pagar_con( $tarjeta ), False );

	}


}
