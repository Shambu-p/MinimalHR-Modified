<?php

class AccountModel extends CI_Model {

	private string $table_name = "account";

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param $employee_id
	 *            employee's identifier number
	 * @param $old_password
	 *            the password to be changed or replaced
	 * @param $new_password
	 *            the password to be set in place of the previous
	 * @return array
	 * @throws Exception
	 */
	function changePassword($employee_id, $old_password, $new_password){

		$account = $this->db->get_where(
			$this->table_name,
			["employee_id" => $employee_id]
		)->row_array();

		if(empty($account)){
			throw new Exception("account not found");
		}

		if(!password_verify($old_password, $account["password"])){
			throw new Exception("incorrect password");
		}

		$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
		$this->db->update(
			$this->table_name,
			["password" => $hashed_password],
			["employee_id" => $employee_id]
		);

		$account["password"] = $hashed_password;

		return $account;

	}

	/**
	 * @param $id
	 * @param $status
	 * @throws Exception
	 */
	function updateAccountStatus($id, $status){

		$account = $this->getAccount($id);

		if(!sizeof($account)){
			throw new Exception("account not found!");
		}

		if($account["status"] == $status){
			return $account;
		}

		$this->db->update($this->table_name, [
			"status" => $status
		], [
			"employee_id" => $id
		]);

		if($status == "suspended"){

			$dt = new DateTime();

			$this->db->update("eventdate", [
				"prohibition_start_date" => $dt->getTimestamp()
			], [
				"employee_id" => $id
			]);

		}else if($status == "active"){

			$dt = new DateTime();

			$this->db->update("eventdate", [
				"prohibition_end_date" => $dt->getTimestamp()
			], [
				"employee_id" => $id
			]);

		}else {

			$dt = new DateTime();

			$this->db->update("eventdate", [
				"termination_date" => $dt->getTimestamp()
			], [
				"employee_id" => $id
			]);

		}

		$account["status"] = $status;
		return $account;

	}

	/**
	 * get an employee by using email address
	 * @param string $email
	 *            email address
	 * @return array|array[]|object|object[]
	 */
	function getAccountByEmail(string $email) {

		$result = $this->db->get_where($this->table_name, ['email' => $email])->result();
		return sizeof($result) ? $result[0] : [];

	}

	/**
	 * returns all accounts depending on the parameters given to it.
	 * @param bool $is_admin
	 * @param string|null $status
	 * @return array|array[]|object|object[]
	 */
	function get($is_admin = false, $status = null){

		$condition = [ "is_admin" => $is_admin ];

		if($status){
			$condition["status"] = $status;
		}

		return $this->db->get_where($this->table_name, $condition)->result();

	}

	/**
	 * @param int $id
	 *
	 * @return array|mixed|object
	 */
	function getAccount(int $id) {

		$result = $this->db->get_where($this->table_name, ['employee_id' => $id])->result_array();
		return (sizeof($result) > 0) ? $result[0] : [];

	}

	function accountDetail(int $id){

		$result = (array) $this->getAccount($id);
		if(!sizeof($result)){
			return [];
		}

		$detail = $this->db->get_where("employee", ["id" => $id])->result_array();
		$address = $this->db->get_where("address", ["employee_id" => $id])->result_array();
		$event_date = $this->db->get_where("eventdate", ["employee_id" => $id])->result_array();
		return (sizeof($detail) > 0) ? [
			"account" => $result,
			"detail" => $detail[0],
			"address" => $address,
			"event_date" => sizeof($event_date) ? $event_date[0] : []
		]: [];

	}

	function allAccounts($request){

		$query = $this->db->select("*")->from($this->table_name . " A")
			->join("employee E", 'E.id = A.employee_id');

		if(isset($request["status"])){
			$query->where("A.status", $request["status"]);
		}

		if(isset($request["department_id"])){
			$query->where("E.department_id", $request["department_id"]);
		}

		return (array) $query->get()->result();

	}

}
