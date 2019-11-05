<?php

namespace TrabajoTarjeta;


trait Pagos{

 protected $CostoViaje = 14.80;

 function getCostoViaje(){
	return $this->CostoViaje;
 }

 function getCostoMedioBoleto(){
	return $this->CostoViaje / 2;
 }
 function getCostoTransbordo(){
	return 0.0;
 }

}
