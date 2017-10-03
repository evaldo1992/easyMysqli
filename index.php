<?php
	//Incluye la clase easyInsert
	include('easyInsert.php');
	
	//Realiza la coneccion a tu bd
	$mysql = new mysqli('localhost','root','','bd');
	if ( $mysql->connect_errno ){
		echo $mysql->connect_errno;
		exit();
	}
	$mysql->set_charset('utf8');
	$mysql->query("SET NAMES 'utf8'");
	$mysql->query("SET lc_time_names = 'es_ES'");
	
	//Instancia la clase EasyInsert
	$person=new EasyInsert($mysql,'tbl_people');
	$person->addField('name','evaldo','s');
	$person->addField('last_name','vega','s');
	if($person->insert()){
		echo 'Person created id generate '.$person->getId();
	}else{
		echo $person->getError();
	}
?>