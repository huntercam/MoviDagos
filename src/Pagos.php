<?php

namespace TrabajoTarjeta;


trait Pagos{

 protected $CostoViaje = 14.80;

 function getCostoViaje(){
	return $CostoViaje;
 }

}
