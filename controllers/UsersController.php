<?php

class UsersController{
  function create($params){
    $user = User::Create($params);
    if (true){
      return array(true, $user);
    } else {
      return array(false, null);
    }
  }
  function _list($params){
    return array(true, User::All());
  }
  function show($id, $params){
    return array(true, User::Get($id));
  }
  function update($id, $params){
    $user = User::Get($id);
    $user->set_attributes($params);
    $result = $user->update();
    $errors = null;
    if (!$result){
      $errors = $user->errors();
    }
    return array($result, $errors);
  }
  function destroy($id, $params){
    $user = User::Get($id);
    $result = $user->destroy();
    $errors = null;
    if (!$result){
      $errors = $user->errors();
    }
    return array($result, $errors);
  }
}