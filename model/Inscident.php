<?php
require_once('CRUD.php');
require_once('Model.php');
class Inscident extends Model{
	protected $table = 'incident';
	private $id;
	private $description;
	private $longitude;
	private $latitude;
	private $time;
	private $obs;
	use CRUD;
	public function __construct(Array $data=[]){
		if(count($data)>0){
		  $this->fill($data);
		}
	}
	public function getId(){
		return $this->id;
	}
	public function setId(int $id){
		$this->id = $id;
	}
	public function getDescription(){
		return $this->description;
	}
	public function setDescription(string $description){
		$this->description = $description;
	}
	public function getLongitide(){
		return $this->longitude;
	}
	public function setLongitude($longitude){
		$this->longitude = $longitude;
	}
	public function getLatitide(){
		return $this->latitude;
	}
	public function setLatitude($latitude){
		$this->latitude = $latitude;
	}
	public function getTime(){
		return $this->time;
	}
	public function setTime($time){
		$this->time = $time;
	}
	public function getObs(){
		return $this->obs;
	}
	public function setObs(string $obs){
		$this->obs = $obs;
	}
	public function fill($data){
		$this->setLongitude((double)$data['longitude']);
		$this->setLatitude((double)$data['latitude']);
		$this->setDescription($data['description']);
		$this->setTime($data['time']);
		$this->setObs($data['obs']);
		$this->setDescription($data['description']);
		if(isset($data['id'])){
			$this->setId((int)$data['id']);
		}
	}
	public function toArray(){
		return [
			'id' => $this->getId(),
			'description' => $this->getDescription(),
			'latitude' => $this->getLatitide(),
			'longitude' => $this->getLongitide(),
			'time' => $this->getTime(),
			'obs'=>$this->getObs()

		];
	}

}