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

		return $insert;

	}

	function deleteAddress($request) {

		$condition = [
			"employee_id" => $request["employee_id"],
			"city" => $request["city"],
			"sub_city" => $request["sub_city"],
		];

		if(isset($request["phone_number"])) {
			$condition["phone_number"] = $request["phone_number"];
		}

		if(isset($request["place_name"])) {
			$condition["place_name"] = $request["place_name"];
		}

		if(isset($request["street_name"])) {
			$condition["street_name"] = $request["street_name"];
		}

		$this->db->delete($this->table_name, $condition);

	}

}
