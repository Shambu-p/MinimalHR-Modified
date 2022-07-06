<?php

error_reporting(!E_DEPRECATED );

//require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/core/API_Controller.php';
//use Restserver\Libraries\REST_Controller;

class Auth extends API_Controller {

    function __construct() {
		parent::__construct();
	}

	function login_post() {

        try {

            $this->load->model('AccountModel');
            $email_matched_user = (array) $this->AccountModel->getAccountByEmail($this->input->post("email"));

            if(empty($email_matched_user)) {
				$this->response(["message" => "account not found!"], 200);
				return;
			}

            if($email_matched_user["status"] != "active"){
				$this->response(["message" => "Your account is not activated! contact the administrator"], 200);
				return;
			}

            if(!password_verify($this->input->post("password"), $email_matched_user["password"])){
				$this->response(["message" => "Incorrect email or password"], 200);
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
		$this->response($this->auth_user, 200);
    }

    function logout($token) {
//        Response::prepare([]);
    }

}
