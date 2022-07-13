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
	function registerEmployee(array $request) {

		$this->load->library("Utils");
		$utils = new Utils();
		$final_array = $utils->prepare_employee_data($request);

		$this->db->insert($this->table_name, $final_array["employee"]);
		$address = $final_array["address"];
		$final_array["address"] = [];

		$employee_id = $this->db->insert_id();
		$generated_application_number = isset($request["vacancy_id"]) ? intval($request["vacancy_id"] . $employee_id) : intval($employee_id . 0);

		$this->db->update(
			$this->table_name,
			["application_number" => $generated_application_number],
			["id", $employee_id]
		);

		$final_array["employee"]["id"] = $employee_id;
		$final_array["employee"]["application_number"] = $generated_application_number;

		foreach($address as $single_address) {
			$single_address->employee_id = $employee_id;
			$final_array["address"][] = (array) $single_address;
		}

		$this->db->insert_batch("address", $final_array["address"]);

		if(isset($final_array["account"])) {

			$password = $final_array["account"]["password"];
			$final_array["account"]["password"] = password_hash($password, PASSWORD_DEFAULT);
			$final_array["account"]["employee_id"] = $employee_id;

			$this->db->insert('account', $final_array["account"]);
			$this->db->insert('eventdate', [
				"employee_id" => $employee_id,
				"work_start_date" => date("Y-m-d h:i:s")
			]);
			$final_array["account"]["password"] = $password;

		}

		return $final_array;

	}

	/**
	 * get an employee by using email address
	 * @param string $email
	 * 			email address
	 */
	function getEmployeeByEmail(string $email){
		return $this->db->get_where($this->table_name, ['email' => $email])->result();
	}

	function getApplications($request){

		$condition = [];
		if(isset($request["application_status"])){
			$condition["status"] = $request["application_status"];
		}

		if(isset($request["salary"])){
			$condition["salary"] = $request["salary"];
		}

		if(isset($request["department_id"])){
			$condition["employee_department"] = $request["department_id"];
		}

		if(isset($request["position"])){
			$condition["position"] = $request["position"];
		}

		return $this->db->get_where($this->table_name, $condition)->result_array();

	}

	function getApplication(int $application_number) {

		$condition = ["application_number" => $application_number];
		return $this->db->get_where(
			$this->table_name,
			$condition
		)->row_array();

	}

	function getEmployee($id){
		return $this->db->get_where($this->table_name, ['id' => $id])->row_array();
	}

	/**
	 * @param int $id
	 * 			the employee id
	 * @param String $profile_picture
	 * 			the new employee profile picture file name
	 */
	function changeProfilePicture(int $id, String $profile_picture){
		$this->db->update(
			$this->table_name,
			["profile_picture" => $profile_picture,],
			["id" => $id]
		);
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

		$set_array = [
			"full_name" => $request["full_name"],
			"email" => $request["email"],
			"salary" => $request["salary"],
			"phone_number" => $request["phone_number"],
			"education_level" => $request["education_level"],
			"employee_department" => $request["department_id"],
			"position" => $request["position"]
		];

		$this->db->update(
			$this->table_name,
			$set_array, ["id" => $request["id"]]
		);

	}

	/**
	 * changes application status if it is changed to accepted then it will create none admin account
	 * @param int $id
	 *            employee id
	 * @param String $status
	 *            application status
	 * @return array
	 */
	function updateApplicationStatus(int $id, String $status) {

		$employee = (array) $this->getEmployee($id);
		$final_array = [];

		if(empty($employee)){
			return [];
		}

		$this->db->update(
			$this->table_name,
			["status" => $status],
			["id" => $id]
		);

		$employee["status"] = $status;
		$final_array["employee"] = $employee;

		$this->load->library("Utils");
		$utils = new Utils();

		if($status == "accepted") {

			$password = $utils->passwordGenerator();
			$final_array["account"] = [
				"employee_id" => $id,
				"email" => $employee["email"],
				"status" => "active",
				"is_admin" => false,
				"password" => password_hash($password, PASSWORD_DEFAULT)
			];


			$this->db->insert('account', $final_array["account"]);
			$final_array["account"]["password"] = $password;

			$this->db->insert('eventdate', [
				"employee_id" => $this->db->insert_id(),
				"work_start_date" => date("Y-M-d h:i:s")
			]);

		}

		return $final_array;

	}

	function byApplicationNumber(int $application_number) {

		return $this->db->get_where(
			$this->table_name,
			["application_number" => $application_number]
		)->row_array();

	}

}
