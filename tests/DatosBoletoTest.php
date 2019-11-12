<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class Datos_Boleto_Test extends TestCase {
use Costos;
    /**
     * Comprueba que los datos de un boleto se asignan correctamente al pagar con una tarjeta en un colectivo
     * 
     * @return void
     */

    public function test_boleto_normal() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( NULL );
        $colectivo = new Colectivo( 'mixta', '103', 420 );
        $tarjeta->recargar( 20.0 );
        $boleto = $tarjeta->pagarConTarjeta( $colectivo , $tiempo_prueba );
        $this->assertEquals( $boleto->getValor(), $this->getCostoViaje() );
        $this->assertEquals( $boleto->getTarjeta()->getSaldo(), 20.0 - $this->getCostoViaje() );
        $this->assertEquals( $boleto->getTarjeta()->getId(), NULL );
        $this->assertEquals( $boleto->getColectivo()->linea(), '103' );
        $this->assertEquals( $boleto->getTipoBoleto(), 'Normal' );
    }

    /**
     * Comprueba que los datos de un boleto se asignan correctamente al usar un viaje plus  con una tarjeta en un colectivo
     * 
     * @return void
     */
    public function test_boleto_plus() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( NULL );
        $colectivo = new Colectivo( 'mixta', '103', 420 );
        $boleto = $tarjeta->pagarConTarjeta( $colectivo , $tiempo_prueba );
        $this->assertEquals( $boleto->getValor(), 0.0 );
        $this->assertEquals($boleto->getTarjeta()->getId(), NULL );
        $this->assertEquals($boleto->getColectivo()->linea(), '103' );
        $this->assertEquals($boleto->getTipoBoleto(), 'Viaje Plus' );
    }

    /**
     * Comprueba que los datos de un boleto se asignan correctamente al pagar con una tarjeta en un colectivo cuando se debe un viaje plus
     * 
     * @return void
     */
    public function test_boleto_pagando_plus() {
        $tiempo_prueba = new Tiempo();
        $tarjeta = new Tarjeta( NULL );
        $colectivo = new Colectivo( 'mixta', '103', 420 );
        $tarjeta->gastarPlus();
        $tarjeta->recargar( 50.0 );
        $boleto = $tarjeta->pagarConTarjeta( $colectivo , $tiempo_prueba );
        $this->assertEquals( $boleto->getValor(), $this->getCostoViaje() * 2 );
        $this->assertEquals( $boleto->getTarjeta()->getSaldo(), 50.0 - ($this->getCostoViaje() * 2) );
        $this->assertEquals( $boleto->getTarjeta()->getId() , NULL );
        $this->assertEquals( $boleto->getColectivo()->linea(), '103' );
        $this->assertEquals( $boleto->getTipoBoleto(), 'Pagando Plus' );
    }
}