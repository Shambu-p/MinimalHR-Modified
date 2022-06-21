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

		$employee = (array) $this->db->get_where($this->table_name, ["employee_id" => $employee_id])->result();
		if(!sizeof($employee)){
			throw new Exception("account not found");
		}

		$account = (array) $employee[0];
		if(!password_verify($old_password, $account["password"])){
			throw new Exception("incorrect password");
		}

		$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
		$this->db->set("password", $hashed_password);
		$this->db->where("employee_id", $employee_id);
		$this->db->update($this->table_name);

		$account["password"] = $hashed_password;

		return $account;

	}

	/**
	 * get an employee by using email address
	 * @param string $email
	 *            email address
	 * @return array|array[]|object|object[]
	 */
	function getAccountByEmail(string $email){

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

		$result = $this->db->get_where($this->table_name, ['employee_id' => $id])->result();
		return (sizeof($result) > 0) ? $result[0] : [];

	}

	function accountDetail(int $id){

		$result = $this->getAccount($id);
		if(!sizeof($result)){
			return [];
		}

		$detail = $this->db->get_where("employee", ["id" => $id])->result();
		return (sizeof($detail) > 0) ? [
			"account" => $result,
			"detail" => $detail[0]
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
