<?php

namespace TrabajoTarjeta;


trait Costos{

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
 
 function getMontoPromo1(){
	return 1119.90;
 }
 function getMontoAdicionalPromo1(){
	return 180.10;
 }
 
 function getMontoPromo2(){
	return 2114.11;
 }
 function getMontoAdicionalPromo2(){
	return 485.89;
 }

}
