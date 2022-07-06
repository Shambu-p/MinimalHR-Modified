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
					$this->input->post("old_password"),
					$this->input->post("new_password")
				),
				200
			);

		} catch (Exception $ex){
			$this->response(["message" => $ex->getMessage()]);
		}

	}

	function profile_picture_get($employee_id){

		$employee_detail = $this->EmployeeModel->getEmployee($employee_id);

		if(!sizeof($employee_detail)){
			$this->response(["message" => "employee not found"], 200);
			return;
		}

		$file = "./uploads/profile_pictures/".$employee_detail["profile_picture"];
		if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: image/png');
			// change inline to attachment if you want to download it instead
			header('Content-Disposition: inline; filename="'.basename($file).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			readfile($file);
		}

		$this->response(["message" => "cannot read image"], 200);

	}

	function change_profile_picture_post(){

		$this->authenticate("admin", true);

		$this->load->library("upload", [
			'upload_path' => './uploads/profile_pictures',
			'file_name' => 'profile_pic_' . $this->auth_user["email"] . '.png',
			'allowed_types' => ['jpg', 'png', 'ico', 'jpeg'],
			'max_size' => 1000,
			'overwrite' => TRUE
		]);

		if(!$this->upload->do_upload('profile_picture')) {
			$this->response(
				["message" => "image file: " . $this->upload->display_errors()],
				200
			);
			return;
		}

		$profile_upload = $this->upload->data();
		$this->EmployeeModel->updateProfilePicture($this->auth_user["employee_id"], $profile_upload["file_name"]);
		$this->response(["profile_picture" => $profile_upload['file_name']], 200);

	}

	function suspend_user(){}

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
	function change_application_status_post(){

		$this->authenticate("admin", true);
		$result = $this->EmployeeModel->updateApplicationStatus(
			$this->input->post("application_id"),
			$this->input->post("status")
		);
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
	function application_detail_post(){

		$this->authenticate("admin", true);
		$this->response(
			$this->EmployeeModel->byApplicationNumber($this->input->post("application_number")),
			200
		);

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
		$this->response($this->EmployeeModel->byApplicationNumber($application_number), 200);
	}

	function apply_and_register(){

		$this->load->library('upload', [
			'upload_path' => './uploads/profile_pictures',
			'file_name' => 'profile_pic_' . $this->input->post("email") . '.png',
			'allowed_types' => ['jpg', 'png', 'ico', 'jpeg'],
			'max_size' => 1000
		]);

		if(!$this->upload->do_upload('profile_picture')){
			$this->response(
				["message" => "image file: " . $this->upload->display_errors()],
				200
			);
			return;
		}else{
			$profile_upload = $this->upload->data();
		}

		$this->upload = null;
		$this->load->library('upload', [
			'upload_path' => './uploads/documents',
			'file_name' => 'application_doc_'.$this->input->post("email").'.zip',
			'allowed_types' => ['zip'],
			'max_size' => 1000
		]);

		if(!$this->upload->do_upload('documents')){
			$this->response(
				["message" => "document file: " . $this->upload->display_errors()],
				200
			);
			return;
		}else{
			$document_upload = $this->upload->data();
		}

		$requests = $this->input->post();
		$requests["profile_picture"] = $profile_upload["file_name"];
		$requests["documents"] = $document_upload["file_name"];

		$this->response($this->EmployeeModel->registerEmployee($requests), 200);

	}

	function apply_for_vacancy_post(){
		$this->apply_and_register();
	}

}
