<?php

class User {
	public $id;
	public $name;
	static function All(){
		$u1 = new User();
		$u1->id = 1;
		$u1->name = "Claudia";
		$u2 = new User();
		$u2->id = 2;
		$u2->name = "Cynthia";
		return array($u1, $u2);
	}
}