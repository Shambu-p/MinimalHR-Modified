<?php

class Utils {

	function prepare_employee_data ($request) {

		$final_array = [
			"employee" => [],
			"address" => (array) json_decode($request["address"])
		];

		unset($request["address"]);
		unset($request["token"]);
		$vacancy_id = $request["vacancy_id"] ?? null;

		if(!$vacancy_id) {

			$final_array["account"] = [
				"email" => $request["email"],
				"status" => "active",
				"is_admin" => false,
				"password" => $this->passwordGenerator()
			];

		}else{
			unset($request["vacancy_id"]);
		}

		$final_array["employee"] = $request;
		return $final_array;

	}

	function passwordGenerator(){

		$comb = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array();
		$combLen = strlen($comb) - 1;
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $combLen);
			$pass[] = $comb[$n];
		}
		return implode($pass);

	}

}
