<?php

require APPPATH . '/core/API_Controller.php';

class Employees extends API_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("EmployeeModel");
	}

	/**
	 * creates new employee on employee table
	 * the requester will be authenticated using sent token
	 * parameters using post method
	 * @parameters using post method
	 * full_name
	 * email
	 * profile_picture
	 * documents
	 * salary
	 * phone_number
	 * education_level
	 * department_id
	 * address
	 * @address is json string containing array of address for example
	 */
	function register_employee_post(){

		$this->authenticate("admin", true);
		$this->apply_and_register();

	}

	/**
	 * changes employee password identified by employee id
	 */
	function change_password_post(){

		$this->authenticate("admin", true);

		if($this->input->post("new_password") != $this->input->post("confirm_password")){
			$this->response([
				"message" => "password confirmation doesn't match with the new password"
			], 200);
		}

		try{

			$this->load->model("AccountModel");

			$this->response(
				$this->AccountModel->changePassword(
					$this->auth_user["employee_id"],
					$this->input->post("new_password"),
					$this->input->post("old_password")
				),
				200
			);

		} catch (Exception $ex){
			$this->response(["message" => $ex->getMessage()]);
		}

	}

	/**
	 * responds with profile picture image file content of and employee record
	 * @param $employee_id
	 */
	function profile_picture_get($employee_id){

		$employee_detail = $this->EmployeeModel->getEmployee($employee_id);

		if(empty($employee_detail)){
			$this->response(["message" => "employee not found"], 200);
		}

		$file = "./uploads/profile_pictures/".$employee_detail["profile_picture"];
		$this->respond_file($file, 'image/png');

	}

	/**
	 * responds with applicant document zip file content of an employee record
	 * @param $employee_id
	 */
	function document_get($employee_id){

		$employee_detail = $this->EmployeeModel->getEmployee($employee_id);

		if(empty($employee_detail)){
			$this->response(["message" => "employee not found"], 200);
		}

		$file_path = "./uploads/documents/".$employee_detail["documents"];
		$this->respond_file($file_path, 'application/zip');

	}

	function change_profile_picture_post(){

		$this->authenticate("admin", true);

		$file_name = 'profile_pic_' . $this->auth_user["email"] . '.png';
		$profile_upload = $this->my_upload($file_name, 'profile_picture', TRUE);
		$this->EmployeeModel->changeProfilePicture($this->auth_user["employee_id"], $profile_upload["file_name"]);
		$this->response(["profile_picture" => $profile_upload['file_name']], 200);

	}

	function change_status(){}

	function delete_user(){}

	/**
	 * returns specific employee detail which is identified by employee_id
	 * @param $employee_id string
	 * @param $token string
	 * this is  a php function
	 *
	 * parameters should be sent using get method
	 */
	function employee_detail_post(){

		$this->authenticate("admin", true);
		$this->load->model("AccountModel");
		$this->response(
			$this->AccountModel->accountDetail(
				($this->auth_user["is_admin"] && isset($_POST["employee_id"])) ? $this->input->post("employee_id") : $this->auth_user["employee_id"]
			),
			200
		);

	}

	/**
	 * returns employees list after authenticating the requester
	 * using authentication token
	 * @param $token
	 * parameter token should be sent using get method
	 */
	function employee_list_post(){

		$this->authenticate("admin", true);
		$this->load->model("AccountModel");
		$this->response($this->AccountModel->allAccounts($this->input->post()), 200);

	}

	/**
	 * change application status attribute at employee table
	 * after authenticating the requesting user using passed token
	 * @parameters using post method
	 * application_status
	 * token
	 * application_id
	 */
	function change_application_status_post() {

		$this->authenticate("admin", true);
		$result = $this->EmployeeModel->updateApplicationStatus(
			$this->input->post("application_id"),
			$this->input->post("status")
		);

		if(isset($result["account"])) {

			$this->load->library('email');
			$this->load->library('Utils');

			$mail_object = $this->Utils->account_creation_email($this->email, $result["account"]["email"], $result["account"]["password"]);
			$mail_object->send(true);

		}

		$this->response($result, 200);

	}

	/**
	 * deletes application which is identified by application number
	 * @parameters using post method
	 *   application_number
	 * 	 token
	 */
	function delete_application(){}

	/**
	 * @param $application_id int
	 * @param $token string
	 * returns the detail of application which will be identified by application id
	 */
	function application_detail_post() {

		$this->authenticate("admin", true);
		$this->check_application_get($this->input->post("application_number"));

	}

	/**
	 * @param $application_status
	 * @param $department_id
	 * @param $vacancy_id
	 * returns all applicant list depending on
	 * application status, department id and vacancy id
	 */
	function application_list_post(){

		$this->authenticate("admin", true);
		$this->response($this->EmployeeModel->getApplications($this->input->post()), 200);

	}

	/**
	 * @param $application_number
	 * checking application for applicant existance
	 * and returning detail of the application
	 */
	function check_application_get($application_number){

		$this->load->model("DepartmentModel");
		$this->load->model("AddressModel");

		$application = $this->EmployeeModel->byApplicationNumber($application_number);
		if(empty($application)){
			$this->response([], 200);
		}

		$address = $this->AddressModel->employeeAddress($application["id"]);
		$department = $this->DepartmentModel->departmentDetail($application["employee_department"]);

		$this->response([
			"detail" => $application,
			"address" => $address,
			"department" => $department
		],200);

	}

	function apply_and_register(){

		$profile_file_name = 'profile_pic_' . $this->input->post("email") . '.png';
		$document_file_name = 'application_doc_'.$this->input->post("email").'.zip';

		$profile_upload = $this->my_upload($profile_file_name, 'profile_picture');
		$document_upload = $this->my_upload($document_file_name, 'documents');

		$requests = $this->input->post();
		$requests["profile_picture"] = $profile_upload["file_name"];
		$requests["documents"] = $document_upload["file_name"];

		$response = $this->EmployeeModel->registerEmployee($requests);

		if(isset($response["account"])) {

			$this->load->library('email');
			$this->load->library('Utils');

			$mail_object = $this->Utils->account_creation_email($this->email, $response["employee"]["email"], $response["account"]["password"]);
			$mail_object->send(true);

		}

		$this->response($response, 200);

	}

	function apply_for_vacancy_post(){
		$this->apply_and_register();
	}

}
