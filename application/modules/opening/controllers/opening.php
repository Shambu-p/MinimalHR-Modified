<?php

//error_reporting(!E_DEPRECATED );

require APPPATH . '/core/Module_Controller.php';

class opening extends Module_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("VacancyModel");
	}

	/**
	 * @throws Exception
	 * @return array
	 */
	function post_vacancy(){

		$this->authenticate("admin", true);
		return $this->VacancyModel->createVacancy($this->input->post());

	}

	function vacancy_detail($id){
		return $this->VacancyModel->singleVacancy($id);
	}

	function all() {
		return $this->VacancyModel->getVacancies($this->input->post());
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	function update_vacancy(){

		$this->authenticate("admin", true);
		$request = $this->input->post();
		$request["updated_by"] = $this->auth_user["employee_id"];
		return $this->VacancyModel->updateVacancy($request);

	}

}
