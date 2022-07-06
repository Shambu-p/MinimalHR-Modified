<?php

//error_reporting(!E_DEPRECATED );

require APPPATH . '/core/API_Controller.php';

class Vacancy extends API_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("VacancyModel");
	}

	function post_vacancy_post(){

		$this->authenticate("admin", true);
		$this->response(
			$this->VacancyModel->createVacancy($this->input->post()),
			200
		);

	}

	function vacancy_detail_get($id){
		$this->response($this->VacancyModel->singleVacancy($id), 200);
	}

	function all_post() {
		$this->response($this->VacancyModel->getVacancies($this->input->post()),200);
	}

	function update_vacancy_post(){

		$this->authenticate("admin", true);
		$request = $this->input->post();
		$request["updated_by"] = $this->auth_user["employee_id"];
		$this->response($this->VacancyModel->updateVacancy($request), 200);

	}

}
