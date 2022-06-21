<?php

//error_reporting(!E_DEPRECATED );

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Vacancy extends REST_Controller {

	function __construct($config = 'rest') {
		parent::__construct($config);
		$this->load->model("VacancyModel");
	}

	function post_vacancy_post(){

		if(!$this->form_validation->run()){
			$this->response([
				"message" => validation_errors()
			], 200);
			return;
		}

		$user = $this->AuthModel->checkAuth($this->authorization_token, $this->input->post("token"));
		if(!$user["is_admin"]){
			$this->response(["message" => "Access Denied!"], 200);
			return;
		}

		$this->response(
			$this->VacancyModel->createVacancy($this->input->post()),
			200
		);

	}

}
