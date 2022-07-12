<?php

require APPPATH . '/core/API_Controller.php';

class Account extends API_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("AccountModel");
	}

	function forgot_password_post(){

		$account = (array) $this->AccountModel->getAccountByEmail($this->input->post("email"));

		if(empty($account)){
			$this->response(["message" => "account not found!!"]);
		}

		try {

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
				$this->response(["message" => "email not sent!"], 200);
			}
			$this->response($account, 200);

		} catch (Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}

	}

	function verify_user_post(){

		$user_code = $this->input->post("verification_code");
		$user = $this->input->post("employee_id");
		$verification = $this->AccountModel->verifyCode($user, $user_code);

		if(empty($verification)) {
			$this->response(["message" => "verification failed!!"], 200);
		}else{
			$this->response($verification, 200);
		}

	}

	function change_status_post(){

		$this->authenticate("admin", true);

		$response = $this->AccountModel->updateAccountStatus($this->input->post("employee_id"), $this->input->post("status"));
		$this->response($response, 200);

	}

	function recover_password_post(){

		$verification = $this->AccountModel->verifyCode(
			$this->input->post("employee_id"),
			$this->input->post("verification_code")
		);

		if(empty($verification)) {
			$this->response(["message" => "verification failed!!"], 200);
		}

		if($this->input->post("new_password") != $this->input->post("confirm_password")){
			$this->response([
				"message" => "password confirmation doesn't match with the new password"
			], 200);
		}

		try{

			$this->response(
				$this->AccountModel->changePassword(
					$this->input->post("employee_id"),
					$this->input->post("new_password")
				),
				200
			);

		} catch (Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}

	}

}
