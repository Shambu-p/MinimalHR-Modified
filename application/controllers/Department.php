<?php

error_reporting(!E_DEPRECATED );
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Department extends REST_Controller {


	function __construct() {

		header('Access-Control-Allow-Origin: *');
		parent::__construct();
		$this->load->model("DepartmentModel");
		$this->load->model("AuthModel");

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

		try{

			if(!$this->form_validation->run()){
				$this->response([
					"message" => validation_errors()
				], 500);
				return;
			}

			$user = $this->AuthModel->checkAuth($this->authorization_token, $this->input->post("token"));
			if(!$user["is_admin"]){
				$this->response(["message" => "Access Denied!"], 200);
				return;
			}

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

		} catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}

	}

	/**
	 * change details of the department identified by department id
	 * passed to this request handler.
	 */
	function update_post(){

		try{

			if(!$this->form_validation->run()){
				$this->response([
					"message" => validation_errors()
				], 500);
				return;
			}

			$user = $this->AuthModel->checkAuth($this->authorization_token, $this->input->post("token"));
			if(!$user["is_admin"]){
				$this->response(["message" => "Access Denied!"], 200);
				return;
			}

			$this->response(
				$this->DepartmentModel->updateDepartment(
					$this->input->post("department_id"),
					isset($_POST["department_name"]) ? $this->input->post("department_name") : null,
					isset($_POST["department_head"]) ? $this->input->post("department_head") : null
				),
				200
			);

		} catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}

	}

	function all_get(){
		$this->response((array) $this->DepartmentModel->getAll(), 200);
	}

	function department_detail_get(int $id){
		$this->response($this->DepartmentModel->departmentDetail($id), 200);
	}

}
