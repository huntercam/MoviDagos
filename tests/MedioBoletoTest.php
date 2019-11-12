<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class medio_boletoTest extends TestCase {
use Costos;
    /**
     * Comprueba que el medio boleto page la mitad que una tarjeta normal en un pago estandar.
     * 
     * @return void
     */
    public function test_pagar_monto_estandar() {
        $tiempo = new Tiempo();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto( Null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $medio_boleto->recargar( 50.0 );
		$boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $boleto->getValor(), $this->getCostoMedioBoleto() );
    }
    
    /**
     * Comprueba que el medio boleto page la mitad que una tarjeta normal en un pago con un viaje plus acumulado
     * 
     * @return void
     */
    public function test_pagar_monto_viaje_plus_simple() {
        $tiempo = new Tiempo();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto( Null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $medio_boleto->recargar( 50.0 );
        $medio_boleto->gastarPlus();
        $this->assertEquals( $medio_boleto->getViajesPlus(), 1 );
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $boleto->getValor(), $this->getCostoMedioBoleto() + $this->getCostoViaje() );
    }
    
    /**
     * Comprueba que el medio boleto page la mitad que una tarjeta normal en un pago con dos viajes plus acumulados
     * 
     * @return void
     */
    public function test_pagar_monto_viaje_plus_doble() {
        $tiempo = new Tiempo();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto( Null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $medio_boleto->recargar( 50.0 );
        $medio_boleto->gastarPlus();
        $medio_boleto->gastarPlus();
        $this->assertEquals( $medio_boleto->getViajesPlus(), 0 );
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $boleto->getValor(), $this->getCostoMedioBoleto() + (2 * $this->getCostoViaje() ) );
    }

    /**
     * Comprueba que no se deja marcar un segundo medio boleto en un intervalo menor a 5 minutos
     * 
     * @return void
     */
    
    public function test_medio_boleto_tiempo() {
        $tiempo = new Tiempo();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto( Null );
        $medio_boleto->recargar( 50.0 );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $tiempo->avanzar( 240 );
        $boleto2 = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $boleto2->getValor(), $this->getCostoViaje() );
    }
    
    /**
     * Comprueba que se pueden utilizar viajes plus con un medio boleto
     * 
     * @return void
     */
    public function test_medio_boleto_viaje_plus(){
        $tiempo = new Tiempo();
        $tiempo->avanzar(36000);
        $medio_boleto = new Tarjeta_Medio_Boleto( Null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
		
        $this->assertEquals( $medio_boleto->getViajesPlus(), 2 );
		
        $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $medio_boleto->getViajesPlus(), 1 );
		
        $medio_boleto->gastarPlus();
        $this->assertEquals( $medio_boleto->getViajesPlus(), 0 );
		
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $boleto->getTipoBoleto(), 'Saldo Insuficiente' );
    }

    /**
     * Comprueba que es posible utilizar trasbordo con un medio boleto
     * 
     * @return void
     */
    public function test_medio_boleto_trasbordo_normal(){
        $tiempo = new Tiempo();
        $tiempo->avanzar(36000);
        $medio_boleto = new Tarjeta_Medio_Boleto( Null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $colectivo2 = new Colectivo( 'mixta', '102', 421 );
		
        $medio_boleto->recargar( 50.0 );
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
		
        $this->assertEquals( $boleto->getValor() , $this->getCostoMedioBoleto() );
		
        $tiempo->avanzar( 300 );
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo2 , $tiempo );
        $this->assertEquals( $boleto->getValor(), $this->getCostoTransbordo() );
    }

    /**
     * Comprueba que se puede usar trasbordo con un viaje plus en un medio boleto pero el viaje plus no se ve afectado
     * 
     * @return void
     */
    public function test_medio_boleto_trasbordo_CUVP(){
        $tiempo = new Tiempo();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto( Null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $colectivo2 = new Colectivo( 'mixta', '102', 421 );
		
        $medio_boleto->recargar( 50.0 );
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
		
        $this->assertEquals( $boleto->getValor(), $this->getCostoMedioBoleto() );
        $medio_boleto->gastarPlus();
		
        $tiempo->avanzar( 300 );
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo2 , $tiempo );
		
        $this->assertEquals( $boleto->getValor(), $this->getCostoViaje() );
    }

    /**
     * Comprueba que se puede usar trasbordo con dos viajes plus en un medio boleto pero los viajes plus no se ven afectados
     * 
     * @return void
     */
    public function test_medio_boleto_trasbordo_plus(){
        $tiempo = new Tiempo();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto( Null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $colectivo2 = new Colectivo( 'mixta', '102', 421 );
        $medio_boleto->recargar( 50.0 );
		
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $boleto->getValor(), $this->getCostoMedioBoleto() );
		
        $medio_boleto->gastarPlus();
        $medio_boleto->gastarPlus();
        $tiempo->avanzar( 300 );
		
        $boleto = $medio_boleto->pagarConTarjeta( $colectivo2 , $tiempo );
        $this->assertEquals( $boleto->getValor(), $this->getCostoViaje()*2 );
    }
}
