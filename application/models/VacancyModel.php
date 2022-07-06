<?php

class VacancyModel extends CI_Model {

	private String $table_name = "vacancy";

	function __construct(){
		parent::__construct();
	}

	function createVacancy($request){

		$vacancy = $this->prepareVacancyData($request);

		$this->db->insert($this->table_name, $vacancy);
		$vacancy["id"] = $this->db->insert_id();
		return $vacancy;

	}

	function singleVacancy($id){

		$result = $this->db->get_where($this->table_name, ["id" => $id]);

		if(!$result->num_rows()){
			return [];
		}

		$vacancy = $result->row_array();

		$updated_by = $this->db->get_where("employee", ["id" => $vacancy["updated_by"]])->row_array();
		$department = $this->db->get_where("department", ["id", $vacancy["department_id"]])->row_array();

		return [
			"detail" => $vacancy,
			"updated_by" => $updated_by,
			"department" => $department
		];

	}

	function getVacancies($request){

		$query = $this->db->select("department.name as department_name, vacancy.id, vacancy.position, vacancy.description, vacancy.salary, vacancy.start_date, vacancy.end_date, vacancy.status, vacancy.updated_by, vacancy.department_id")
			->from($this->table_name)
			->join("department", "$this->table_name.department_id  = department.id");

		if(isset($request["status"])){
			$query->like("status", $request["status"]);

			if($request["status"] == "open"){
				$query->where("end_date >", "current_timestamp()", true);
			}else{
				$query->where("end_date <", "current_timestamp()", true);
			}
		}

		if(isset($request["department_id"])){
			$query->like("vacancy.department_id", $request["department_id"]);
		}

		if(isset($request["position"])){
			$query->like("vacancy.position", $request["position"]);
		}

		return $query->get()->result_array();

	}

	function prepareVacancyData($request) {
		return [
			"position" => $request["position"],
			"salary" => $request["salary"],
			"description" => $request["description"],
			"start_date" => $request["start_date"],
			"end_date" => $request["end_date"],
			"status" => $request["status"],
			"updated_by" => $request["employee_id"],
			"department_id" => $request["department_id"]
		];
	}

	function updateVacancy($request){

		$vacancy = $this->prepareVacancyData($request);

		$this->db->update(
			$this->table_name,
			$vacancy,
			["id" => $request["vacancy_id"]]
		);

		return $request;

	}

	function deleteVacancy($request){

	}

}
