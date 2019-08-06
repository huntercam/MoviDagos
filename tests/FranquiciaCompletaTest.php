<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class Franquicia_Completa_Test extends TestCase {

    /**
     * Comprueba que la franquicia completa siempre pueda pagar.
     * 
     * @return void
     */
    public function test_pagar_monto_estandar() {
        $tiempo = new Tiempo();
        $franquicia_completa = new Tarjeta_Franquicia_Completa ( $tiempo, null );
        $colectivo = new Colectivo( 'mixta', '103', 420 );
        $this->assertEquals( $colectivo->pagar_con( $franquicia_completa ), new Boleto( $colectivo, $franquicia_completa ) );
        $this->assertEquals( $franquicia_completa->obtener_saldo(), ( 0.0 ) );
    }
    
}
