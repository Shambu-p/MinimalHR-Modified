<?php

$config = [

	'Employees/register_employee' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'full_name',
			'label' => 'full_name',
			'rules' => 'required|regex_match[/(\w\s?)+/]|max_length[100]|min_length[10]'
		],
		[
			'field' => 'email',
			'label' => 'email',
			'rules' => 'required|valid_email|is_unique[employee.email]|max_length[50]'
		],
		[
			'field' => 'salary',
			'label' => 'salary',
			'rules' => 'required|numeric'
		],
		[
			'field' => 'phone_number',
			'label' => 'phone_number',
			'rules' => 'required|trim|regex_match[/(\+2519|09)\d{8}/]|max_length[14]'
		],
		[
			'field' => 'education_level',
			'label' => 'education_level',
			'rules' => 'required|in_list[ba,bsc,beng,llb,marts,mbiol,mcomp,meng,mmath,mphys,msci,ma,msc,mba,mphil,mres,llm,phd]'
		],
		[
			'field' => 'department_id',
			'label' => 'department_id',
			'rules' => 'required|integer'
		],
		[
			'field' => 'position',
			'label' => 'position',
			'rules' => 'required|trim|regex_match[/(\w\s?)+/]|max_length[100]'
		],
		[
			'field' => 'address',
			'label' => 'address',
			'rules' => 'required'
		]
	],
	'Employees/change_password' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'old_password',
			'label' => 'old_password',
			'rules' => 'required|max_length[20]|min_length[8]'
		],
		[
			'field' => 'new_password',
			'label' => 'new_password',
			'rules' => 'required|max_length[20]|min_length[8]'
		],
		[
			'field' => 'confirm_password',
			'label' => 'confirm_password',
			'rules' => 'required|max_length[20]|min_length[8]'
		],
	],
	'Employees/change_profile_picture' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'employee_id',
			'label' => 'employee_id',
			'rules' => 'required|integer'
		]
	],
	'Employees/profile_picture' => [
		[
			'field' => 'employee_id',
			'label' => 'employee_id',
			'rules' => 'required|integer'
		]
	],
	'Employees/application_detail' => [
		[
			'field' => 'application_number',
			'label' => 'application_number',
			'rules' => 'required|integer'
		],
		[
			'field' => 'application_number',
			'label' => 'application_number',
			'rules' => 'required|integer'
		]
	],
	'Employees/application_list' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'application_status',
			'label' => 'application_status',
			'rules' => 'in_list[pending,viewed,accepted,rejected]'
		],
		[
			'field' => 'vacancy_id',
			'label' => 'vacancy_id',
			'rules' => 'integer'
		],
		[
			'field' => 'department_id',
			'label' => 'department_id',
			'rules' => 'integer'
		]
	],
	'Employees/employee_list' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'status',
			'label' => 'status',
			'rules' => 'in_list[active,disabled,suspended]'
		],
		[
			'field' => 'department_id',
			'label' => 'department_id',
			'rules' => 'integer'
		]
	],
	'Employees/change_application_status' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'status',
			'label' => 'status',
			'rules' => 'required|in_list[accepted,rejected,viewed,pending]'
		],
	],
	'Employees/employee_detail' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'employee_id',
			'label' => 'employee_id',
			'rules' => 'integer'
		]
	],

	'Department/create' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'name',
			'label' => 'name',
			'rules' => 'required|regex_match[/(\w\s?)+/]|max_length[100]|min_length[10]'
		],
		[
			'field' => 'department_head',
			'label' => 'department_head',
			'rules' => 'integer|max_length[100]|min_length[10]'
		]
	],
	'Department/update' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'department_id',
			'label' => 'department_id',
			'rules' => 'required|integer'
		],
		[
			'field' => 'department_name',
			'label' => 'department_name',
			'rules' => 'regex_match[/(\w\s?)+/]|max_length[100]|min_length[10]'
		],
		[
			'field' => 'department_head',
			'label' => 'department_head',
			'rules' => 'integer'
		],
	],

	'Vacancy/post_vacancy' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'description',
			'label' => 'description',
			'rules' => 'required|regex_match[/(\w\s?)+/]'
		],
		[
			'field' => 'salary',
			'label' => 'salary',
			'rules' => 'required|numeric'
		],
		[
			'field' => 'status',
			'label' => 'status',
			'rules' => 'required|in_list[active,inactive]'
		],
		[
			'field' => 'start_date',
			'label' => 'start_date',
			'rules' => 'required'
		],
		[
			'field' => 'end_date',
			'label' => 'end_date',
			'rules' => 'required'
		],
		[
			'field' => 'employee_id',
			'label' => 'employee_id',
			'rules' => 'required|integer'
		],
		[
			'field' => 'department_id',
			'label' => 'department_id',
			'rules' => 'required|integer'
		],
		[
			'field' => 'position',
			'label' => 'position',
			'rules' => 'required|trim|regex_match[/(\w\s?)+/]|max_length[100]'
		]
	],
	'Vacancy/all' => [
		[
			'field' => 'position',
			'label' => 'position',
			'rules' => 'trim|regex_match[/(\w\s?)+/]|max_length[100]'
		],
		[
			'field' => 'department_id',
			'label' => 'department_id',
			'rules' => 'integer'
		],
		[
			'field' => 'status',
			'label' => 'status',
			'rules' => 'in_list[open,closed]'
		]
	],

	'Auth/login' => [
		[
			'field' => 'email',
			'label' => 'email',
			'rules' => 'required|valid_email|max_length[50]'
		],
		[
			'field' => 'password',
			'label' => 'password',
			'rules' => 'required|max_length[20]|min_length[8]'
		]
	],
	'Auth/authorization' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		]
	],

	'Address/add_address' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'employee_id',
			'label' => 'employee_id',
			'rules' => 'required|integer'
		],
		[
			'field' => 'city',
			'label' => 'city',
			'rules' => 'required'
		],
		[
			'field' => 'sub_city',
			'label' => 'sub_city',
			'rules' => 'required'
		],
		[
			'field' => 'phone_number',
			'label' => 'phone_number',
			'rules' => 'required|trim|regex_match[/(\+2519|09)\d{8}/]|max_length[14]'
		],
	],
	'Address/edit_address' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'id',
			'label' => 'id',
			'rules' => 'required|integer'
		],
		[
			'field' => 'employee_id',
			'label' => 'employee_id',
			'rules' => 'required|integer'
		],
		[
			'field' => 'city',
			'label' => 'city',
			'rules' => 'required'
		],
		[
			'field' => 'sub_city',
			'label' => 'sub_city',
			'rules' => 'required'
		],
		[
			'field' => 'phone_number',
			'label' => 'phone_number',
			'rules' => 'required|trim|regex_match[/(\+2519|09)\d{8}/]|max_length[14]'
		],
	],
	'Address/delete_address' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'id',
			'label' => 'id',
			'rules' => 'required|integer'
		],
		[
			'field' => 'employee_id',
			'label' => 'employee_id',
			'rules' => 'integer'
		]
	],

	'Account/forgot_password' => [
		[
			'field' => 'email',
			'label' => 'email',
			'rules' => 'required|valid_email|max_length[50]'
		]
	]

];
