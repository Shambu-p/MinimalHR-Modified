<?php


require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;


class Address extends REST_Controller {

	function __construct($config = 'rest'){
		parent::__construct($config);
		$this->load->model("AddressModel");
		$this->load->model("AuthModel");
	}

	function add_address(){

		try{

			if(!$this->form_validation->run()){
				$this->response([
					"message" => validation_errors()
				], 200);
				return;
			}

			$user = $this->AuthModel->checkAuth($this->authorization_token, $this->input->post("token"));
			if(!$user["is_admin"]){

				$address = $this->input->post();
				$address["employee_id"] = $user["employee_id"];
				$this->response($this->AddressModel->addAddress($address), 200);
				return;

			}

			$this->response($this->AddressModel->addAddress($this->input->post()), 200);

		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}

	}

	function delete_address() {

		try{

			if(!$this->form_validation->run()){
				$this->response([
					"message" => validation_errors()
				], 200);
				return;
			}

			$user = $this->AuthModel->checkAuth($this->authorization_token, $this->input->post("token"));

			if(!$user["is_admin"]) {

				$address = $this->input->post();
				$address["employee_id"] = $user["employee_id"];
				$this->response($this->AddressModel->deleteAddress($address), 200);
				return;

			}

			$this->response($this->AddressModel->deleteAddress($this->input->post()), 200);

		} catch(Exception $ex) {
			$this->response(["message" => $ex->getMessage()], 200);
		}

	}

}
