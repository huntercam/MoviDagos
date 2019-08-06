<?php
namespace TrabajoTarjeta;

class Colectivo implements Colectivo_Interface {

    protected $empresa;

    protected $linea;

    protected $numero;

    /**
     * Constructor de la clase que asigna los valores a las propiedades del objeto cuando se crea un colectivo
     * 
     * @param string $linea
     *      La linea del colectivo
     * @param string $empresa
     *      La empresa a la cual pertenece el colectivo
     * @param int $numero
     *      El numero de unidad del colectivo
     * 
     * @return void
     */
    public function __construct( $empresa, $linea, $numero ) {
        $this->empresa = $empresa;
        $this->linea = $linea;
        $this->numero = $numero;
    }

    /**
     * Devuelve el nombre de la empresa
     * 
     * @return string
     */
    public function empresa(): string {
        return $this->empresa;
    }

    /**
     * Devuelve el nombre de la linea
     * 
     * @return string
     */
    public function linea(): string {
        return $this->linea;
    }


    /**
     * Devuelve el numero de unidad
     * 
     * @return string
     */
    public function numero(): int {
        return $this->numero;
    }

    /**
     * Paga un viaje en el colectivo con una tarjeta
     * 
     * @param Tarjeta_Interface $tarjeta
     *      La tarjeta con la cual se paga el viaje
     * 
     * @return Boleto_Interface/bool
     *      En caso de que se pague el viaje con éxito se devuelve un boleto con todos los datos de la operacion y en caso contrario se devuelve false 
     */
    public function pagar_con( Tarjeta_Interface $tarjeta ) {
        if( ! $tarjeta->pagar_tarjeta( $this ) ) {
            return false;
        }
        return new Boleto( $this, $tarjeta );
    }

}
