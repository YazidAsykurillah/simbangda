<?php
class DB{

	protected $db_host = 'localhost';
	protected $db_user = 'root';
	protected $db_password = '';
	protected $db_name = 'simbangdabaru';

	protected $mysqli = FALSE;
	protected $sql;
	protected $result = [];

	public function __construct(){

		if($this->mysqli == FALSE){
			$this->mysqli = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);
		}
		//return $this->mysqli;
		
	}


	public function sql($sql){

		$this->sql = $sql;
		
		$doQuery = $this->mysqli->query($this->sql);
		if($doQuery->num_rows){
			while($row = $doQuery->fetch_object()){
				$this->result[] = $row;
			}
		}
		else{
			$this->result[] = 0;
		}

		return $this->result;

	}

	public function disconnect(){
		if($this->mysqli == FALSE){
			return FALSE;
		}
		else{
			return $this->mysqli->close();
		}
	}



}