<?php

require APPPATH . '/core/REST_Controller.php';

class Address extends API_Controller {

	function __construct($config = 'rest'){
		parent::__construct($config);
		$this->load->model("AddressModel");
	}

	function add_address_post(){

		if($this->authenticate("admin", false)) {

			$address = $this->input->post();
			$address["employee_id"] = $this->auth_user["employee_id"];
			$this->response($this->AddressModel->addAddress($address), 200);
			return;

		}

		$this->response($this->AddressModel->addAddress($this->input->post()), 200);

	}

	function edit_address_post(){

		if($this->authenticate("admin", false)){

			$address = $this->input->post();
			$address["employee_id"] = $this->auth_user["employee_id"];
			$this->response($this->AddressModel->editAddress($address), 200);

		}

		$this->response($this->AddressModel->editAddress($this->input->post()), 200);

	}

	function delete_address_post() {

		if($this->authenticate("admin", false)) {

			$address = $this->input->post();
			$address["employee_id"] = $this->auth_user["employee_id"];
			$this->response($this->AddressModel->deleteAddress($address), 200);

		}

		$this->response($this->AddressModel->deleteAddress($this->input->post(), 200));

	}

}
