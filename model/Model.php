<?php
require_once('db.php');
abstract class Model{
	protected $conn;
	public function db(){
		$db = DB::getInstance();
		$this->conn = $db->conn();
		return $this->conn;
	}
}