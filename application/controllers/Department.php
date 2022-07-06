<?php

//error_reporting(!E_DEPRECATED );
require APPPATH . '/core/API_Controller.php';

class Department extends API_Controller {


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
	 */
	function create_post(){

		$this->authenticate("admin", true);

		if(isset($_POST["department_head"])){

			$this->response(
				$this->DepartmentModel->createDepartment(
					$this->input->post("name"),
					$this->input->post("department_head")
				),
				200
			);

		}else{

			$this->response(
				$this->DepartmentModel->createDepartment(
					$this->input->post("name")
				),
				200
			);

		}

	}

	/**
	 * change details of the department identified by department id
	 * passed to this request handler.
	 */
	function update_post(){

		$this->authenticate("admin_role", true);

		$this->response(
			$this->DepartmentModel->updateDepartment(
				$this->input->post("department_id"),
				isset($_POST["department_name"]) ? $this->input->post("department_name") : null,
				isset($_POST["department_head"]) ? $this->input->post("department_head") : null
			),
			200
		);

	}

	function all_get(){
		$this->response((array) $this->DepartmentModel->getAll(), 200);
	}

	function department_detail_get(int $id){
		$this->response($this->DepartmentModel->departmentDetail($id), 200);
	}

}
