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

		$insert = [
			"employee_id" => $request["employee_id"],
			"city" => $request["city"],
			"sub_city" => $request["sub_city"]
		];

		if(isset($request["phone_number"])){
			$insert["phone_number"] = $request["phone_number"];
		}

		if(isset($request["place_name"])){
			$insert["place_name"] = $request["place_name"];
		}

		if(isset($request["street_name"])){
			$insert["street_name"] = $request["street_name"];
		}

		$this->db->insert($this->table_name, $insert);
		$insert["id"] = $this->db->insert_id();

		return $insert;

	}

	function editAddress($request) {

		$address = [
			"employee_id" => $request["employee_id"],
			"city" => $request["city"],
			"sub_city" => $request["sub_city"]
		];

		if(isset($request["phone_number"])){
			$address["phone_number"] = $request["phone_number"];
		}

		if(isset($request["place_name"])){
			$address["place_name"] = $request["place_name"];
		}

		if(isset($request["street_name"])){
			$address["street_name"] = $request["street_name"];
		}

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
