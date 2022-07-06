<?php

class DepartmentModel extends CI_Model {

	private string $table_name = "department";

	/**
	 * creating new department
	 * @param string $name
	 *          the department name or title
	 * @param int $department_head
	 *          employee id which exist in employee table
	 *          assigns department head for the department
	 * @return array
	 */
	function createDepartment(string $name, int $department_head = 0){

		if($department_head){
			$this->db->insert($this->table_name, [
				"name" => $name,
				"department_head" => $department_head
			]);
		}else{
			$this->db->insert($this->table_name, [
				"name" => $name
			]);
		}

		return [
			"name" => $name,
			"department_head" => $department_head
		];

	}

	/**
	 * changing department details
	 * @param int $department_id
	 *          department identifier number
	 * @param string|null $department_name
	 *          the new department title or name to be changed
	 *          in place of the previous name or title
	 * @param int|null $department_head
	 *          employee's identifier number which wanted to be assigned
	 *          for the department head position in place of the previous head
	 * @return array
	 */
	function updateDepartment(int $department_id, $department_name = null, $department_head = null){

		if(!$department_head && !$department_name){
			return [];
		}

		if($department_name){
			$this->db->set("name", $department_name);
		}

		if($department_head){
			$this->db->set("department_head", $department_head);
		}

		$this->db->where("id", $department_id);
		$this->db->update($this->table_name);

		return [
			"id" => $department_id,
			"name" => $department_name,
			"department_head" => $department_head
		];

	}

	function getAll() {
		return $this->db->get($this->table_name)->result_array();
	}

	function departmentDetail(int $department_id){
		return $this->db->get_where($this->table_name, ["id" => $department_id])->row_array();
	}

}
