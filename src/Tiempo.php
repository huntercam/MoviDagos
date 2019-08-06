<?php

namespace TrabajoTarjeta;

class Tiempo implements Tiempo_Interface {

    protected $feriados = array(
        '19-11-18',
        '08-12-18',
        '24-12-18',
        '25-12-18',
        '31-12-18',
        '01-01-19',
        '04-03-19',
        '05-03-19',
        '25-03-19',
        '02-04-19',
        '19-04-19',
        '01-05-19',
        '25-05-19',
        '17-06-19',
        '20-06-19',
        '08-07-19',
        '09-07-19',
        '17-08-19',
        '19-08-19',
        '12-10-19',
        '14-10-19',
        '18-11-19',
        '08-12-19',
    );

    /**
     * Devuelve el tiempo actual
     * 
     * @return int
     */
    public function time() {
        return time();
    }

    /**
     * Indica si el dia actual es un feriado
     * 
     * @return bool
     *      Devuelve true si es un feriado y false en caso contrario
     */
    public function es_feriado(){
        $fecha = date( 'd-m-y', $this->time() );
        return in_array( $fecha, $this->feriados );
    }
}

class Tiempo_Falso implements Tiempo_Interface {

    protected $tiempo;

    protected $feriados = array(
        '01-01', //  Año Nuevo
        '24-03', //  Día Nacional de la Memoria por la Verdad y la Justicia.
        '02-04', //  Día del Veterano y de los Caídos en la Guerra de Malvinas.
        '01-05', //  Día del trabajador.
        '25-05', //  Día de la Revolución de Mayo.
        '17-06', //  Día Paso a la Inmortalidad del General Martín Miguel de Güemes.
        '20-06', //  Día Paso a la Inmortalidad del General Manuel Belgrano. F
        '09-07', //  Día de la Independencia.
        '17-08', //  Paso a la Inmortalidad del Gral. José de San Martín
        '12-10', //  Día del Respeto a la Diversidad Cultural
        '20-11', //  Día de la Soberanía Nacional
        '08-12', //  Inmaculada Concepción de María
        '25-12', //  Navidad
    );

    public function __construct( $inicio = 0 ) {
        $this->tiempo = $inicio;
    }
    
    /**
     * Avanza el tiempo un segundo
     * 
     * @return void
     */
    public function avanzar( $segundos ) {
        $this->tiempo += $segundos;
    }

    /**
     * Devuelve el tiempo actual
     * 
     * @return int
     */
    public function time() {
        return $this->tiempo;
    }

    /**
     * Indica si el dia actual es un feriado
     * 
     * @return bool
     *      Devuelve true si es un feriado y false en caso contrario
     */
    public function es_feriado() {
        $fecha = date( 'd-m', $this->tiempo );
        return in_array( $fecha, $this->feriados );
    }
}
