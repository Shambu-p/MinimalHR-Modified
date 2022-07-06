<?php

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class API_Controller extends REST_Controller {

	public array $auth_user = [];

	function __construct($config = 'rest') {

		header('Access-Control-Allow-Origin: *');

		parent::__construct($config);

		if(strtolower($_SERVER["REQUEST_METHOD"]) == "post") {

			if(!$this->form_validation->run()) {
				$this->response(["message" => validation_errors()], 200);
			}

		}

		$this->getUserByToken();

	}

	function getUserByToken(){

		if(!isset($_POST["token"])){
			return;
		}

		$result = (array) $this->authorization_token->validateToken($_POST["token"]);
		if(!$result["status"]){
			$this->response(["message" => $result["message"]], 200);
		}

		$this->auth_user = (array) $result["data"];

	}

	/**
	 * @param string $role
	 * 		user role this case [admin, non_admin]
	 * @param boolean $respond_if_false
	 * 		if check result is required leave this field false
	 * 		but if json response is needed then true
	 * @return bool
	 */
	function authenticate(string $role, bool $respond_if_false){

		if($role == "admin" && !$this->auth_user["is_admin"]) {
			return $this->authenticationResponse($respond_if_false);
		}

		return true;

	}

	/**
	 * @param $respond_or_return
	 * @return false
	 */
	function authenticationResponse($respond_or_return) {

		if($respond_or_return){
			$this->response(["message" => "access denied!"], 200);
			return false;
		}else{
			return false;
		}

	}

}
