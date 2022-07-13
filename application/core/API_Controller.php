<?php

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class API_Controller extends REST_Controller {

	public array $auth_user = [];

	function __construct() {

		header('Access-Control-Allow-Origin: *');

		parent::__construct();

		if(strtolower($_SERVER["REQUEST_METHOD"]) == "post") {

			if(!$this->form_validation->run()) {
				$this->response(["message" => validation_errors()], 200);
			}

		}

	}

}
