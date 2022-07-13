<?php

//error_reporting(!E_DEPRECATED );
require APPPATH . '/core/Module_Controller.php';

class department extends Module_Controller {

	function __construct() {

		parent::__construct();
		$this->load->model("DepartmentModel");

	}

	/**
	 * create new department using department model
	 * parameters needed are
	 * name
	 * department_head
	 *
	 * department head is not needed
	 * but if department head needs to be assigned then it can be specified here
	 * or it can be assigned in assign_department_head request
	 * @throws Exception
	 *
	 */
	function create(){

		$this->authenticate("admin", true);

		if(isset($_POST["department_head"])){

			return $this->DepartmentModel->createDepartment(
				$this->input->post("name"),
				$this->input->post("department_head")
			);

		}else{

			return $this->DepartmentModel->createDepartment(
				$this->input->post("name")
			);

		}

	}

	/**
	 * change details of the department identified by department id
	 * passed to this request handler.
	 * @throws Exception
	 */
	function update() {

		$this->authenticate("admin", true);

		return $this->DepartmentModel->updateDepartment(
			$this->input->post("department_id"),
			isset($_POST["department_name"]) ? $this->input->post("department_name") : null,
			isset($_POST["department_head"]) ? $this->input->post("department_head") : null
		);

	}

	function all(){
		return (array) $this->DepartmentModel->getAll();
	}

	function department_detail(int $id){

		$department = $this->DepartmentModel->departmentDetail($id);
		if(empty($department)){
			return [];
		}

		$this->load->module("user");
		if($department["department_head"]){
			$head = $this->user->get_employee($department["department_head"]);
			$department["department_head"] = $head["full_name"] ?? "";
		}

		return $department;

	}

	/**
	 * all the department properties
	 * without department head but still the department head id
	 * @param $id
	 * @return array
	 */
	function get_department($id){
		return $department = $this->DepartmentModel->departmentDetail($id);
	}

}
