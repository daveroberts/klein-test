<?php

class UsersController extends JSONController{
	function _list(){
    return User::All();
  }
  function create($params){
    $user = User::Create($params);
    $result = $user->save();
    if ($result){
      return array(true, $user);
    } else {
      return array(false, $user->errors());
    }
  }
  function show($id){
    return User::Get($id);
  }
  function update($params){
    $user = User::Get($params->id);
    $user->set_attributes($params);
    $result = $user->save();
    $errors = array();
    if (!$result){
      $errors = $user->errors();
    }
    return array($result, $errors);
  }
  function destroy($id){
    $user = User::Get($id);
    $result = $user->destroy();
    $errors = array();
    if (!$result){
      $errors = $user->errors();
    }
    return array($result, $errors);
  }
}