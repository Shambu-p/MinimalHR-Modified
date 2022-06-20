<?php
error_reporting(!E_DEPRECATED );

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Employees extends REST_Controller {

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
	// CgzTTOk4
	function register_employee_post(){

		if(!$this->form_validation->run()){
			$this->response([
				"message" => validation_errors()
			], 200);
			return;
		}

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

	/**
	 * changes employee password identified by employee id
	 */
	function change_password_post(){

		if(!$this->form_validation->run()){
			$this->response([
				"message" => validation_errors()
			], 200);
			return;
		}

		if($this->input->post("new_password") != $this->input->post("confirm_password")){
			$this->response([
				"message" => "password confirmation doesn't match with the new password"
			], 200);
		}

		$this->load->model("AccountModel");

		try{

			$this->response(
				$this->AccountModel->changePassword(
					$this->input->post("employee_id"),
					$this->input->post("old_password"),
					$this->input->post("new_password")
				),
				200
			);

		} catch(Exception $exception) {
			$this->response(
				["message" => $exception->getMessage()],
				200
			);
		}

	}

	function profile_picture_post(){

		if(!$this->form_validation->run()){
			$this->response([
				"message" => validation_errors()
			], 200);
			return;
		}

		$jwt = new JWT();
		$user = $jwt->decode($this->input->post("token"), "my_secret_key", 'HS256');

		$employee_detail = $this->EmployeeModel->getEmployeeById($user["employee_id"]);

		if(sizeof($employee_detail)){
			$this->response(["message" => "employee not found"], 200);
		}

		$file = "./uploads/profile_pictures/".$employee_detail["profile_picture"];
		if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/pdf');
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

	function change_profile_picture(){

		if(!$this->form_validation->run()){
			$this->response([
				"message" => validation_errors()
			], 200);
			return;
		}

		$jwt = new JWT();
		$user = (array) json_decode($jwt->decode($this->input->post("token"), "my_secret_key", 'HS256'));

		$this->load->library("upload", [
			'upload_path' => './uploads/profile_pictures',
			'file_name' => 'profile_pic_' . $user["email"] . '.png',
			'allowed_types' => ['jpg', 'png', 'ico', 'jpeg'],
			'max_size' => 1000,
			'overwrite' => TRUE
		]);

		if(!$this->upload->do_upload('profile_picture')){
			$this->response(
				["message" => "image file: " . $this->upload->display_errors()],
				200
			);
			return;
		}

		$profile_upload = $this->upload->data();
		$this->EmployeeModel->updateProfilePicture($user["id"], $profile_upload["file_name"]);
		$this->response(["profile_picture" => $profile_upload['file_name']], 200);

	}

	function suspend_user(){

	}

	function delete_user(){

	}

	/**
	 * returns specific employee detail which is identified by employee_id
	 * @param $employee_id string
	 * @param $token string
	 * this is  a php function
	 *
	 * parameters should be sent using get method
	 */
	function employee_detail(string $employee_id, string $token){

	}

	/**
	 * returns employees list after authenticating the requester
	 * using authentication token
	 * @param $token
	 * parameter token should be sent using get method
	 */
	function employee_list($token){

	}

	/**
	 * creates application on employee table without authentication
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
	 *  [
	 * 		{
	 * 			"email": "the email",
	 * 			"phone_number": "+251983475985", //can be empty
	 * 			"city": "my city",
	 * 			"sub_city": "my sub city",
	 * 			"place_name": "place name can be empty",
	 * 			"street_name": "street name can be empty"
	 * 		},
	 *      {
	 * 			"email": "the email",
	 * 			"phone_number": "+251983475985", //can be empty
	 * 			"city": "my city",
	 * 			"sub_city": "my sub city",
	 * 			"place_name": "place name can be empty",
	 * 			"street_name": "street name can be empty"
	 * 		}
	 * 	]
	 *
	 */
	function apply(){
		echo json_encode(["hello"]);
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

		$jwt = new JWT();
		$user = (array) json_decode($jwt->decode($this->input->post("token"), "my_secret_key", 'HS256'));

		$result = $this->EmployeeModel->updateApplicationStatus($user["employee_id"], $this->input->post("status"));

		$this->response($result, 200);
	}

	/**
	 * creates account and change application status to 'accepted'
	 * @parameters using post method
	 * application_status
	 * token
	 * application_id
	 */
	function accept_application(){

	}

	/**
	 * deletes application which is identified by application number
	 * @parameters using post method
	 *   application_number
	 * 	 token
	 */
	function delete_application(){

	}

	/**
	 * @param $application_id int
	 * @param $token
	 * returns the detail of application which will be identified by application id
	 */
	function application_detail(int $application_id, $token){

	}

	/**
	 * @param $application_status
	 * @param $department_id
	 * @param $vacancy_id
	 * returns all applicant list depending on
	 * application status, department id and vacancy id
	 */
	function application_list($application_status, $department_id, $vacancy_id){

	}

	/**
	 * @param $application_number
	 * checking application for applicant existance
	 * and returning detail of the application
	 */
	function check_application($application_number){

	}

}
