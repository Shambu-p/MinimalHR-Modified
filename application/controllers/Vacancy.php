<?php

//error_reporting(!E_DEPRECATED );

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Vacancy extends REST_Controller {

	function __construct($config = 'rest') {
		header('Access-Control-Allow-Origin: *');
		parent::__construct($config);
		$this->load->model("VacancyModel");
		$this->load->model("AuthModel");
	}

	function post_vacancy_post(){

		try{

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

		} catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}

	}

	function vacancy_detail_get($id){

		$this->response(
			$this->VacancyModel->sigleVacancy($id),
			200
		);

	}

	function all_post() {

		if(!$this->form_validation->run()){
			$this->response([
				"message" => validation_errors()
			], 200);
			return;
		}

		$this->response(
			$this->VacancyModel->getVacancies($this->input->post()),
			200
		);

	}

	function update_vacancy_post(){

		try{

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

			$request = $this->input->post();
			$request["updated_by"] = $user["employee_id"];
			$this->response($this->VacancyModel->updateVacancy($request), 200);

		}catch (Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}

	}

}
