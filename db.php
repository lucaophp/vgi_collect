<?php

class DB{
	
	public static $server;
	public static $dbname;
	public static $user;
	public static $pass;
	private static $inst;
	private function __construct(){
		$ini_array = parse_ini_file("db.ini");
		self::$server = $ini_array['server'];
		self::$dbname = $ini_array['dbname'];
		self::$user = $ini_array['user'];
		self::$pass = $ini_array['pass'];

	}
	public function conn(){
		$dsn = sprintf('mysql:host=%s;dbname=%s',self::$server,self::$dbname);
		$pdo = new PDO($dsn,self::$user,self::$pass);
		return $pdo;
	}
	public static function getInstance(){
		if(self::$inst == null){
			self::$inst = new DB();
		}
		return self::$inst;

	}
}