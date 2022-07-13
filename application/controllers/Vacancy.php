<?php

require APPPATH . '/core/API_Controller.php';

class Vacancy extends API_Controller {

	function __construct() {
		parent::__construct();
		$this->load->module("opening");
	}

	function post_vacancy_post() {
		try{
			$this->response($this->user->post_vacancy(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function vacancy_detail_get($id) {
		try{
			$this->response($this->user->vacancy_detail($id), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function all_post() {
		try{
			$this->response($this->user->all(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function update_vacancy_post() {
		try{
			$this->response($this->user->update_vacancy(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

}
