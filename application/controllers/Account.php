<?php

require APPPATH . '/core/API_Controller.php';

class Account extends API_Controller {

	function __construct($config = 'rest') {
		parent::__construct($config);
		$this->load->model("AccountModel");
	}

	function forgot_password_post(){

		$account = (array) $this->AccountModel->getAccountByEmail($this->input->post("email"));

		if(empty($account)){
			$this->response(["message" => "account not found!!"]);
		}

		try{

			$this->load->library('email');
			$this->email->initialize([
				"protocol" => "smtp",
				"smtp_host" => "smtp.gmail.com",
				"smtp_user" => "abnetkebede075@gmail.com",
				"smtp_pass" => "Shambel4419/09?",
				"smtp_port" => 465
			]);

			$this->email->from('babbikebede21@gmail.com', 'Shambel');
			$this->email->to($account["email"]);

//			$this->email->to('abnetkebede075@gmail.com');
//			$this->email->cc('another@another-example.com');
//			$this->email->bcc('them@their-example.com');

			$this->email->subject('Account Verification');
			$this->email->message('Testing account verification');

			$this->email->send();
			$this->response($account, 200);

		} catch (Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}

	}

	function change_status_post(){

		$this->authenticate("admin", true);

		$response = $this->AccountModel->updateAccountStatus($this->input->post("employee_id"), $this->input->post("status"));
		$this->response($response, 200);

	}

}
