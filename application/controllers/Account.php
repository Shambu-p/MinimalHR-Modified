<?php

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Account extends REST_Controller {

	function __construct($config = 'rest') {
		parent::__construct($config);
		$this->load->model("AccountModel");
	}

	function forgot_password_post(){

		if(!$this->form_validation->run()){
			$this->response([
				"message" => validation_errors()
			], 200);
			return;
		}

		$account = (array) $this->AccountModel->getAccountByEmail($this->input->post("email"));

		if(!sizeof($account)){
			$this->response(["message" => "account not found!!"]);
			return;
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

	function verify_account(){



	}

	function change_password(){

	}

	function change_status(){


	}

}
