<?php

require APPPATH . '/core/Module_Controller.php';

class user extends Module_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("EmployeeModel");
		$this->load->model("AccountModel");
		$this->load->model("AddressModel");
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
	 * @throws Exception
	 */
	function register_employee(){

		$this->authenticate("admin", true);
		return $this->apply_and_register();

	}

	/**
	 * changes employee password identified by employee id
	 * @throws Exception
	 */
	function change_password(){

		if($this->input->post("new_password") != $this->input->post("confirm_password")){
			throw new Exception("password confirmation doesn't match with the new password");
		}

		return $this->AccountModel->changePassword(
			$this->auth_user["employee_id"],
			$this->input->post("new_password"),
			$this->input->post("old_password")
		);

	}

	/**
	 * responds with profile picture image file content of and employee record
	 * @param $employee_id
	 * @throws Exception
	 */
	function profile_picture($employee_id){

		$employee_detail = $this->EmployeeModel->getEmployee($employee_id);

		if(empty($employee_detail)){
			throw new Exception("employee not found");
		}

		$file = "./uploads/profile_pictures/".$employee_detail["profile_picture"];
		$this->respond_file($file, 'image/png');

	}

	/**
	 * responds with applicant document zip file content of an employee record
	 * @param $employee_id
	 * @throws Exception
	 */
	function document($employee_id){

		$employee_detail = $this->EmployeeModel->getEmployee($employee_id);

		if(empty($employee_detail)){
			throw new Exception("employee not found");
		}

		$file_path = "./uploads/documents/".$employee_detail["documents"];
		$this->respond_file($file_path, 'application/zip');

	}

	/**
	 * @throws Exception
	 */
	function change_profile_picture(){

		$this->authenticate("admin", true);

		$file_name = 'profile_pic_' . $this->auth_user["email"] . '.png';
		$profile_upload = $this->my_upload($file_name, 'profile_picture', TRUE);
		$this->EmployeeModel->changeProfilePicture($this->auth_user["employee_id"], $profile_upload["file_name"]);
		return ["profile_picture" => $profile_upload['file_name']];

	}

	/**
	 * returns specific employee detail which is identified by employee_id
	 * @param $employee_id string
	 * @param $token string
	 * this is  a php function
	 *
	 * parameters should be sent using get method
	 * @return array
	 */
	function employee_detail_post() {

		return $this->AccountModel->accountDetail(
			($this->auth_user["is_admin"] && isset($_POST["employee_id"])) ? $this->input->post("employee_id") : $this->auth_user["employee_id"]
		);

	}

	/**
	 * returns employees list after authenticating the requester
	 * using authentication token
	 * @param $token
	 * parameter token should be sent using get method
	 * @return array
	 * @throws Exception
	 */
	function employee_list(){

		$this->authenticate("admin", true);
		return $this->AccountModel->allAccounts($this->input->post());

	}

	/**
	 * change application status attribute at employee table
	 * after authenticating the requesting user using passed token
	 * @parameters using post method
	 * application_status
	 * token
	 * application_id
	 * @throws Exception
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
			$utils = new Utils();

			$mail_object = $utils->account_creation_email($this->email, $result["account"]["email"], $result["account"]["password"]);
			$mail_object->send(true);

		}

		return $result;

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
	 * @return array
	 * @throws Exception
	 */
	function application_detail_post() {

		$this->authenticate("admin", true);
		return $this->check_application_get($this->input->post("application_number"));

	}

	/**
	 * @param $application_status
	 * @param $department_id
	 * @param $vacancy_id
	 * returns all applicant list depending on
	 * application status, department id and vacancy id
	 * @return array
	 * @throws Exception
	 */
	function application_list(){

		$this->authenticate("admin", true);
		return $this->EmployeeModel->getApplications($this->input->post());

	}

	/**
	 * @param $application_number
	 * checking application for applicant existance
	 * and returning detail of the application
	 * @return array
	 */
	function check_application($application_number){

		$application = $this->EmployeeModel->byApplicationNumber($application_number);
		if(empty($application)){
			return [];
		}

		$this->load->module("department");

		$address = $this->AddressModel->employeeAddress($application["id"]);
		$department = $this->department->get_department($application["employee_department"]);

		return [
			"detail" => $application,
			"address" => $address,
			"department" => $department
		];

	}

	/**
	 * @return array
	 * @throws Exception
	 */
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
			$utils = new Utils();

			$mail_object = $utils->account_creation_email($this->email, $response["employee"]["email"], $response["account"]["password"]);
			$mail_object->send(true);

		}

		return $response;

	}

	/**
	 * @return array
	 * @throws Exception
	 */
	function apply_for_vacancy(){
		return $this->apply_and_register();
	}

	/**
	 * all employee properties
	 * @param $id
	 * @return array
	 */
	function get_employee($id){
		return $this->EmployeeModel->getEmployee($id);
	}

	//////////////////////////////// Account controller /////////////////////////////////////////////

	/**
	 * @return array
	 * @throws Exception
	 */
	function forgot_password(){

		$account = (array) $this->AccountModel->getAccountByEmail($this->input->post("email"));

		if(empty($account)){
			throw new Exception("account not found!!");
		}

		$this->load->library('email');
		$this->load->library('Utils');
		$utils = new Utils();

		$verification_code = $utils->passwordGenerator();
		$this->AccountModel->setVerificationCode($account["employee_id"], $verification_code);

		$mail_object = $utils->recovery_pin_message(
			$this->email,
			$verification_code,
			$this->input->post("email")
		);

		if(!$mail_object->send(true)) {
			throw new Exception("email not sent!");
		}

		return $account;

	}

	/**
	 * @return mixed
	 * @throws Exception
	 */
	function verify_user(){

		$user_code = $this->input->post("verification_code");
		$user = $this->input->post("employee_id");
		$verification = $this->AccountModel->verifyCode($user, $user_code);

		if(empty($verification)) {
			throw new Exception("verification failed!!");
		}

		return $verification;

	}

	/**
	 * @throws Exception
	 * @return array
	 */
	function change_status(){

		$this->authenticate("admin", true);
		return $this->AccountModel->updateAccountStatus($this->input->post("employee_id"), $this->input->post("status"));

	}

	/**
	 * @return mixed
	 * @throws Exception
	 */
	function recover_password(){

		$verification = $this->AccountModel->verifyCode(
			$this->input->post("employee_id"),
			$this->input->post("verification_code")
		);

		if(empty($verification)) {
			throw new Exception("verification failed!!");
		}

		if($this->input->post("new_password") != $this->input->post("confirm_password")){
			throw new Exception("password confirmation doesn't match with the new password");
		}

		return $this->AccountModel->changePassword(
			$this->input->post("employee_id"),
			$this->input->post("new_password")
		);

	}

	/////////////////////////// Address Controller /////////////////////////////////

	/**
	 * @throws Exception
	 * @return array
	 */
	function add_address(){

		if($this->authenticate("admin", false)) {

			$address = $this->input->post();
			$address["employee_id"] = $this->auth_user["employee_id"];
			return $this->AddressModel->addAddress($address);

		}

		return $this->AddressModel->addAddress($this->input->post());

	}

	/**
	 * @return array
	 * @throws Exception
	 */
	function edit_address(){

		if($this->authenticate("admin", false)){

			$address = $this->input->post();
			$address["employee_id"] = $this->auth_user["employee_id"];
			return $this->AddressModel->editAddress($address);

		}

		return $this->AddressModel->editAddress($this->input->post());

	}

	/**
	 * @return array
	 * @throws Exception
	 */
	function delete_address_post() {

		if($this->authenticate("admin", false)) {

			$address = $this->input->post();
			$address["employee_id"] = $this->auth_user["employee_id"];
			return $this->AddressModel->deleteAddress($address);

		}

		return $this->AddressModel->deleteAddress($this->input->post());

	}

	/////////////////////////// Auth controller //////////////////////////////

	/**
	 * @return array
	 * @throws Exception
	 */
	function login() {

		$email_matched_user = (array) $this->AccountModel->getAccountByEmail($this->input->post("email"));

		if(empty($email_matched_user)) {
			throw new Exception("account not found!");
		}

		if($email_matched_user["status"] != "active"){
			throw new Exception("Your account is not activated! contact the administrator");
		}

		if(!password_verify($this->input->post("password"), $email_matched_user["password"])){
			throw new Exception("Incorrect email or password");
		}

		unset($email_matched_user["password"]);
		$email_matched_user["token"] = $this->authorization_token->generateToken($email_matched_user);

		return $email_matched_user;

	}

	function authorization() {
		return $this->auth_user;
	}

}
