<?php
require_once('db.php');
require_once('model/Inscident.php');
$long = filter_input(INPUT_POST, 'long');
$lat = filter_input(INPUT_POST, 'lat');
$db = DB::getInstance();
$conn = $db->conn();
$ins = new Inscident(
	[
		'latitude' => $lat,
		'longitude' => $long,
		'description'=>'Inscidente',
		'time' => time(),
		'obs' => ''
	]
);
echo $ins->save();

