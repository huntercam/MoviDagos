<?php

namespace TrabajoTarjeta;


class Tiempo {

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
	
	public function diferencia( $tiempo2 ) {
		$rta = $this->tiempo - $tiempo2;
			if($rta > 0)
				return $rta;
			
        return -$rta;
    }
	public function getTiempo(){
		return $this->tiempo;
	}



    /**
     * Indica si el dia actual es un feriado
     * 
     * @return bool
     *      Devuelve true si es un feriado y false en caso contrario
     */
    public function feriado() {
        $fecha = date( 'd-m', $this->getTiempo() );
        return in_array( $fecha, $this->feriados );
    }
	
	public function diaDeSemana(){
		return  date( 'w', $this->getTiempo() );
	}
	
	public function dentroDeHoras($horainicial, $horafinal){
		return  ( date( 'G', $this->getTiempo() ) >= $horainicial && date( 'G', $this->getTiempo() ) < $horafinal );
	}
}
