<?php


class AddressModel extends CI_Model {

	private $table_name = "address";

	function __construct() {
		parent::__construct();
	}

	function employeeAddress($employee) {
		return $this->db->get_where($this->table_name, ["employee_id" => $employee])->result_array();
	}

	function addAddress($request) {

		$insert = $this->prepare_data($request);

		$this->db->insert($this->table_name, $insert);
		$insert["id"] = $this->db->insert_id();

		return $insert;

	}

	function prepare_data($request){

		$prepared = [
			"employee_id" => $request["employee_id"],
			"city" => $request["city"],
			"sub_city" => $request["sub_city"]
		];

		if(isset($request["phone_number"])){
			$prepared["phone_number"] = $request["phone_number"];
		}

		if(isset($request["place_name"])){
			$prepared["place_name"] = $request["place_name"];
		}

		if(isset($request["street_name"])){
			$prepared["street_name"] = $request["street_name"];
		}

		return $prepared;

	}

	function editAddress($request) {

		$address = $this->prepare_data($request);

		$this->db->update($this->table_name, $address, [
			"id" => $request["id"],
			"employee_id" => $request["employee_id"]
		]);

		return $address;
	}

	function deleteAddress($request) {

		$this->db->delete($this->table_name, [
			"id" => $request["id"],
			"employee_id" => $request["employee_id"]
		]);
		return $request;

	}

}
