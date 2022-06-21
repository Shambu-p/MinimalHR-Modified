<?php

error_reporting(!E_DEPRECATED );

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Auth extends REST_Controller {

    private String $SecretKey = "my_secret_key";

    function __construct() {

		header('Access-Control-Allow-Origin: *');
		parent::__construct();
		$this->load->model("AuthModel");
//		$this->load->library("Authorization_Token");

	}

	function login_post() {

		if(!$this->form_validation->run()){
			$this->response([ "message" => validation_errors()], 201);
			return;
		}

        try {

            $this->load->model('AccountModel');
            $email_matched_user = (array) $this->AccountModel->getAccountByEmail($this->input->post("email"));

            if(!sizeof($email_matched_user) || !password_verify($this->input->post("password"), $email_matched_user["password"])) {
				$this->response(["message" => "incorrect email or password"], 200);
				return;
			}

			unset($email_matched_user["password"]);
			$email_matched_user["token"] = $this->authorization_token->generateToken($email_matched_user);

			$this->response($email_matched_user, 200);

        } catch (Exception $ex) {
            $this->response(["message" => $ex->getMessage()], 200);
        }

    }

    function authorization_post() {

        try {

			if(!$this->form_validation->run()){
				$this->response([
					"message" => validation_errors()
				], 200);
				return;
			}

            $this->response($this->AuthModel->checkAuth($this->authorization_token, $this->input->post("token")), 200);

        } catch (Exception $error) {
			$this->response(["message" => "Exception: " . $error->getMessage()], 200);
        }

    }

    function logout($token) {
//        Response::prepare([]);
    }

}
