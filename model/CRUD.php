<?php
trait CRUD{
	
	public function save(){
		$data = $this->toArray();
		$con = $this->db();
		
		if($this->getId()>0 || $this->getId()!=null){
			//update
			$set = implode(', ', array_map(
			    function ($v, $k) { return sprintf("%s='%s'", $k, $v); },
			    $data,
			    array_keys($data)
			));
			$sql = sprintf('UPDATE %s SET(%s) WHERE(id=%s)',$this->table,$set,$this->getId());
			return $con->query($sql);

		}else{
			//insert
			$val = '\''.implode('\',\'', $data).'\'';
			$k = implode(',', array_keys($data));
			
			$sql = sprintf('INSERT INTO %s (%s) VALUES (%s)',$this->table,$k,$val);
			return $con->query($sql);

		}
	}
	public function delete(){
		$con = $this->db();
		$sql = sprintf('DELETE FROM %s WHERE id = %d',$this->table,$this->getId());
		return $con->query($sql);
	}
	public function getAll(){
		$con = $this->db();
		$sql = sprintf('SELECT * from %s',$this->table);
		$res = $con->query($sql)->fetchAll();
		$class_name = get_class($this);
		
		return array_map(function($v,$k) use ($class_name){
			$inst = new $class_name($v);
			return $inst;
		},$res,array_keys($res));
	}
	public function myDB(){
		return $this->toArray();
	}
}