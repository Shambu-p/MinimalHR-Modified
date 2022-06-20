<?php

class EmployeeModel extends CI_Model {

	private string $table_name = 'employee';

	/**
	 * @param $full_name string
	 *        includes employees name, father's name and grand father's name
	 * @param $email string
	 *        email address
	 * @param $profile_picture string
	 *        image file path on server
	 * @param $document string
	 *        file path on server
	 * @param $salary double
	 *        employee's salary
	 * @param $phone_number string
	 *        employee phone number it may include none number character '+'
	 * @param $education_level string
	 *        it should be one of the following
	 *          ('ba', 'bsc', 'beng', 'llb', 'marts', 'mbiol', 'mcomp', 'meng', 'mmath', 'mphys', 'msci', 'ma', 'msc', 'mba', 'mphil', 'mres', 'llm', 'phd')
	 * @param $department_id int
	 *        department's identifier number
	 * @param string $position
	 * 		  place the employee works on
	 * @param int $vacancy_id
	 *          if this method is called from application request
	 *          handler it needs to specify it vacancy id to which this employee data is created for
	 *          if it is called from register employee method then it should not specify vacancy id
	 * @return array
	 *           returns the inserted data in to the database
	 */
	function registerEmployee(array $request){

		$this->db->insert($this->table_name, [
			"full_name" => $request["full_name"],
			"email" => $request["email"],
			"profile_picture" => $request["profile_picture"],
			"documents" => $request["documents"],
			"salary" => $request["salary"],
			"phone_number" => $request["phone_number"],
			"education_level" => $request["education_level"],
			"employee_department" => $request["department_id"],
			"position" => $request["position"],
			"status" => isset($request["vacancy"]) ? "pending" : "accepted"
		]);

		$employee_id = $this->db->insert_id();
		$generated_application_number = isset($request["vacancy"]) ? intval($request["vacancy"] . $employee_id) : intval($employee_id . $request["vacancy"]);

		$this->db->set("application_number", $generated_application_number);
		$this->db->where("id", $employee_id);
		$this->db->update($this->table_name);

		$address = (array) json_decode($request["address"]);
		$final_array = [
			"employee" => [
				"id" => $employee_id,
				"full_name" => $request["full_name"],
				"email" => $request["email"],
				"profile_picture" => $request["profile_picture"],
				"documents" => $request["documents"],
				"salary" => $request["salary"],
				"phone_number" => $request["phone_number"],
				"education_level" => $request["education_level"],
				"employee_department" => $request["employee_department"],
				"position" => $request["position"],
				"status" => isset($request["vacancy"]) ? "pending" : "accepted",
				"application_number" => $generated_application_number
			],
			"address" => [],

		];

		foreach($address as $single_address){

			$single_address->employee_id = $employee_id;
			$this->db->insert("address", (array) $single_address);
			$final_array["address"][] = (array) $single_address;

		}

		if(!isset($request["vacancy"])){

			$password = $this->passwordGenerator();
			$account = [
				"employee_id" => $employee_id,
				"email" => $request["email"],
				"status" => "active",
				"is_admin" => false,
				"password" => password_hash($password, PASSWORD_DEFAULT)
			];

			$this->db->insert('account', $account);
			$account["password"] = $password;
			$final_array["Account"] = $account;

		}

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

	/**
	 * get an employee by using email address
	 * @param string $email
	 * 			email address
	 */
	function getEmployeeByEmail(string $email){
		return $this->db->get_where($this->table_name, ['email' => $email])->result();
	}

	function getAll(){
		return $this->db->get($this->table_name)->result();
	}

	function getEmployee($id){

		$result = $this->db->get_where($this->table_name, ['id' => $id])->result();
		return (sizeof($result) > 0) ? $result[0] : [];

	}

	/**
	 * @param int $id
	 * 			the employee id
	 * @param String $profile_picture
	 * 			the new employee profile picture file name
	 */
	function changeProfilePicture(int $id, String $profile_picture){
		$this->db->update($this->table_name, [
			"profile_picture" => $profile_picture,
		],["id" => $id]);
	}

	/**
	 * @param array $request
	 * 			all the parameter needed to be updated with their new value
	 * 			[
	 * 				"id" => "",
	 * 				"full_name" => "",
					"email" => "",
					"salary" => "",
					"phone_number" => "",
					"education_level" => "",
					"employee_department" => "",
					"position" => ""
	 * 			]
	 */
	function updateEmployee(array $request) {

		$this->db->update($this->table_name, [
			"full_name" => $request["full_name"],
			"email" => $request["email"],
			"salary" => $request["salary"],
			"phone_number" => $request["phone_number"],
			"education_level" => $request["education_level"],
			"employee_department" => $request["department_id"],
			"position" => $request["position"]
		], [
			"id" => $request["id"]
		]);

		$this->db->update($this->table_name, [
			"email" => $request["email"]
		], [
			"employee_id" => $request["id"]
		]);

	}

	/**
	 * changes application status if it is changed to accepted then it will create none admin account
	 * @param int $id
	 *            employee id
	 * @param String $status
	 *            application status
	 * @return array
	 */
	function updateApplicationStatus(int $id, String $status){

		$employee = (array) $this->getEmployee($id);
		$final_array = [];

		if(!sizeof($employee)){
			return [];
		}

		$this->db->update($this->table_name, [
			"status" => $status
		], [
			"employee_id" => $id
		]);

		$employee["status"] = $status;
		$final_array["employee"] = $final_array;

		if($status == "accepted") {

			$password = $this->passwordGenerator();
			$final_array["account"] = [
				"employee_id" => $id,
				"email" => $employee["email"],
				"status" => "active",
				"is_admin" => false,
				"password" => password_hash($password, PASSWORD_DEFAULT)
			];

			$this->db->insert('account', $final_array);

		}

		return $final_array;

	}

}
