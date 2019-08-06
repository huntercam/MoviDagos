<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class medio_boletoTest extends TestCase {

    /**
     * Comprueba que el medio boleto page la mitad que una tarjeta normal en un pago estandar.
     * 
     * @return void
     */
    public function test_pagar_monto_estandar() {
        $tiempo = new Tiempo_Falso();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto( $tiempo, null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $medio_boleto->recargar( 50.0 );
        $this->assertEquals( $medio_boleto->pagar_tarjeta( $colectivo ), True );
        $this->assertEquals( $medio_boleto->obtener_costo(), 7.4 );
    }
    
    /**
     * Comprueba que el medio boleto page la mitad que una tarjeta normal en un pago con un viaje plus acumulado
     * 
     * @return void
     */
    public function test_pagar_monto_viaje_plus_simple() {
        $tiempo = new Tiempo_Falso();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto( $tiempo, null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $medio_boleto->recargar( 50.0 );
        $medio_boleto->gastar_plus();
        $this->assertEquals( $medio_boleto->obtener_plus(), 1 );
        $medio_boleto->pagar_tarjeta( $colectivo );
        $this->assertEquals( $medio_boleto->obtener_costo(), ( 22.2 ) );
    }
    
    /**
     * Comprueba que el medio boleto page la mitad que una tarjeta normal en un pago con dos viajes plus acumulados
     * 
     * @return void
     */
    public function test_pagar_monto_viaje_plus_doble() {
        $tiempo = new Tiempo_Falso();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto( $tiempo, null );;
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $medio_boleto->recargar( 50.0 );
        $medio_boleto->gastar_plus();
        $medio_boleto->gastar_plus();
        $this->assertEquals( $medio_boleto->obtener_plus(), 0 );
        $medio_boleto->pagar_tarjeta( $colectivo );
        $this->assertEquals( $medio_boleto->obtener_costo(), ( 37.0 ) );
    }

    /**
     * Comprueba que no se deja marcar un segundo medio boleto en un intervalo menor a 5 minutos
     * 
     * @return void
     */
    
    public function test_medio_boleto_tiempo() {
        $tiempo = new Tiempo_Falso();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto( $tiempo, null );
        $medio_boleto->recargar( 50.0 );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $medio_boleto->pagar_tarjeta( $colectivo );
        $tiempo->avanzar( 240 );
        $medio_boleto->pagar_tarjeta( $colectivo );
        $this->assertEquals( $medio_boleto->pagar_tarjeta( $colectivo ), true );
        $this->assertEquals( $medio_boleto->obtener_costo(), 14.80 );
    }
    
    /**
     * Comprueba que se pueden utilizar viajes plus con un medio boleto
     * 
     * @return void
     */
    public function test_medio_boleto_viaje_plus(){
        $tiempo = new Tiempo_Falso();
        $tiempo->avanzar(36000);
        $medio_boleto = new Tarjeta_Medio_Boleto( $tiempo, null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $this->assertEquals( $medio_boleto->obtener_plus(), 2 );
        $medio_boleto->pagar_tarjeta( $colectivo );
        $this->assertEquals( $medio_boleto->obtener_plus(), 1 );
        $medio_boleto->gastar_plus();
        $this->assertEquals( $medio_boleto->obtener_plus(), 0 );
        $medio_boleto->pagar_tarjeta( $colectivo );
        $this->assertEquals( $medio_boleto->pagar_tarjeta( $colectivo ), false );
    }

    /**
     * Comprueba que es posible utilizar trasbordo con un medio boleto
     * 
     * @return void
     */
    public function test_medio_boleto_trasbordo_normal(){
        $tiempo = new Tiempo_Falso();
        $tiempo->avanzar(36000);
        $medio_boleto = new Tarjeta_Medio_Boleto( $tiempo, null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $colectivo2 = new Colectivo( 'mixta', '102', 421 );
        $medio_boleto->recargar( 50.0 );
        $medio_boleto->pagar_tarjeta( $colectivo );
        $this->assertEquals( $medio_boleto->obtener_costo(), 7.40 );
        $tiempo->avanzar( 300 );
        $medio_boleto->pagar_tarjeta( $colectivo2 );
        $this->assertEquals( $medio_boleto->obtener_costo(), 2.442 );
    }

    /**
     * Comprueba que se puede usar trasbordo con un viaje plus en un medio boleto pero el viaje plus no se ve afectado
     * 
     * @return void
     */
    public function test_medio_boleto_trasbordo_CUVP(){
        $tiempo = new Tiempo_Falso();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto( $tiempo, null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $colectivo2 = new Colectivo( 'mixta', '102', 421 );
        $medio_boleto->recargar( 50.0 );
        $medio_boleto->pagar_tarjeta( $colectivo );
        $this->assertEquals( $medio_boleto->obtener_costo(), 7.40 );
        $medio_boleto->gastar_plus();
        $tiempo->avanzar( 300 );
        $medio_boleto->pagar_tarjeta( $colectivo2 );
        $this->assertEquals( $medio_boleto->obtener_costo(), 17.242 );
    }

    /**
     * Comprueba que se puede usar trasbordo con dos viajes plus en un medio boleto pero los viajes plus no se ven afectados
     * 
     * @return void
     */
    public function test_medio_boleto_trasbordo_plus(){
        $tiempo = new Tiempo_Falso();
        $tiempo->avanzar( 36000 );
        $medio_boleto = new Tarjeta_Medio_Boleto( $tiempo, null );
        $colectivo = new Colectivo( 'mixta', '133', 420 );
        $colectivo2 = new Colectivo( 'mixta', '102', 421 );
        $medio_boleto->recargar( 50.0 );
        $medio_boleto->pagar_tarjeta( $colectivo );
        $this->assertEquals( $medio_boleto->obtener_costo(), 7.40 );
        $medio_boleto->gastar_plus();
        $medio_boleto->gastar_plus();
        $tiempo->avanzar( 300 );
        $medio_boleto->pagar_tarjeta( $colectivo2 );
        $this->assertEquals( $medio_boleto->obtener_costo(), 32.042 );
    }
}
