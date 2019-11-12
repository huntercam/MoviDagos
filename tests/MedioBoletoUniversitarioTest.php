<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class medio_boletoUniversitarioTest extends TestCase {
use Costos;
    /**
     * Comprueba que el medio boleto universitario puede usar trasbordo y solo es valido hasta dos veces por dia.
     * 
     * @return void
     */
    public function test_medio_boleto_universitario_tiempo() {
        $tiempo = new Tiempo();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto_Universitario( Null );
        $colectivo = new Colectivo( 'mixta', '103', 420 );
        $colectivo2 = new Colectivo(  'mixta',  '102',  421 );
        $medio_boleto->recargar( 50.0 );
		
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $boleto->getValor(), $this->getCostoMedioBoleto() );
		
        $tiempo->avanzar( 300 );
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $boleto->getValor(), 0.0 );
		
        $tiempo->avanzar( 7200 );
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $boleto->getValor(), $this->getCostoViaje() );
    }

    /**
     * Comprueba que el medio boleto universitario pueda usar hasta dos viajes plus
     * 
     * @return void
     */
    public function test_medio_boleto_un_viajeplus(){
        $tiempo = new Tiempo();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto_Universitario( Null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
		
        $this->assertEquals( $medio_boleto->getViajesPlus(), 2 );
		
        $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $medio_boleto->getViajesPlus(), 1 );
		
        $medio_boleto->gastarPlus();
        $this->assertEquals( $medio_boleto->getViajesPlus() ,0 );
		
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $boleto->getTipoBoleto(), 'Saldo Insuficiente' );
    }

    /**
     * Comprueba que el medio boleto universitario puede usar trasbordo
     * 
     * @return void
     */

    public function test_medio_boleto_trasbordo_normal(){
        $tiempo = new Tiempo();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto_Universitario( Null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $colectivo2 = new Colectivo(  'mixta',  '102',  421 );
        $medio_boleto->recargar( 50.0 );
		
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $boleto->getValor(), $this->getCostoMedioBoleto() );
		
        $tiempo->avanzar( 300 );
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo2 , $tiempo );
        $this->assertEquals( $boleto->getValor(), $this->getCostoTransbordo() );
    }

    /**
     * Comprueba que el medio boleto universitario puede usar trasbordo con un viaje plus pero este no se ve afectado
     * 
     * @return void
     */
    public function test_medio_boleto_trasbordo_CUVP(){
        $tiempo = new Tiempo();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto_Universitario( Null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $colectivo2 = new Colectivo(  'mixta',  '102',  421 );
        $medio_boleto->recargar( 50.0 );
		
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $boleto->getValor(), $this->getCostoMedioBoleto() );
		
        $medio_boleto->gastarPlus();
        $tiempo->avanzar( 300 );
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo2 , $tiempo );
        $this->assertEquals( $boleto->getValor(), $this->getCostoTransbordo() + $this->getCostoViaje()  );
    }

    /**
     * Comprueba que el medio boleto universitario puede usar trasbordo con dos viajes plus pero estos no se ven afectados
     * 
     * @return void
     */
    public function test_medio_boleto_trasbordo_plus(){
        $tiempo = new Tiempo();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto_Universitario( Null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $colectivo2 = new Colectivo(  'mixta',  '102',  421 );
        $medio_boleto->recargar( 50.0 );
		
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $boleto->getValor(), $this->getCostoMedioBoleto() );
		
        $medio_boleto->gastarPlus();
        $medio_boleto->gastarPlus();
        $tiempo->avanzar( 300 );
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo2 , $tiempo );
        $this->assertEquals( $boleto->getValor(), $this->getCostoTransbordo() + ( 2 * $this->getCostoViaje() ) );
    }
}