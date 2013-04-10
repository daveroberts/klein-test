<?php

class User extends ActiveRecord {
	public $id;
	public $name;
  static function All(){
		$sql = "SELECT * FROM `users`";
    global $db;
    $users = array();
    foreach($db->query($sql) as $row){
      $user = new User();
      $user->id = $row['id'];
      $user->name = $row['name'];
      $users[] =$user;
    }
    return $users;
	}
  static function Get($id){
    $sql = "SELECT * FROM `users` WHERE `id`=".$id;
    global $db;
    $row = $db->query($sql)->fetch();
    $user = new User();
    $user->id = $row['id'];
    $user->name = $row['name'];
    return $user;
  }
  static function Create($params){
    $sql = "INSERT INTO `users` (name) VALUES('".$params->name."')";
    global $db;
    $db->exec($sql);
    $id = $db->lastInsertId();
    return User::Get($id);
  }
  function save(){
    return true;
  }
  function destroy(){
    $sql = "DELETE FROM `users` WHERE ID='".$this->id."'";
    global $db;
    $db->exec($sql);
    return true;
  }
  function errors(){
    return array("error1"=>"This is error 1");
  }
}