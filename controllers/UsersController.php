<?php

class UsersController{
	private $data;
	function __init(&$d){
		$this->data = &$d;
	}
	function _list(){
		$this->data['title'] = "List of Users";
		$this->data['users'] = User::All();
		return $this->data;
	}
}