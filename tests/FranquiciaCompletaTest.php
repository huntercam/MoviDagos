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
        $franquicia_completa = new Tarjeta_Franquicia_Completa ( Null );
        $colectivo = new Colectivo( 'mixta', '103', 420 );
		$boleto = $franquicia_completa->pagarConTarjeta( $colectivo , $tiempo );
        $this->assertEquals( $franquicia_completa->getSaldo() ,  0.0  );
    }
    
}
