<?php

class Department extends API_Controller {

	function __construct(){
		parent::__construct();
		$this->load->module("department");
	}

	function create_post() {
		try{
			$this->response($this->department->create(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function update_post(){
		try{
			$this->response($this->department->update(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function all_get(){
		try{
			$this->response($this->department->all(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function department_detail_get(){
		try{
			$this->response($this->department->department_detail(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

}
