<?php

class UsersController{
  function create($params){
    $user = User::Create($params);
    if (true){
      return array(201, $user);
    } else {
      return array(400, null);
    }
  }
  function _list($params){
    return array(200, User::All());
  }
  function show($id, $params){
    return array(200, User::Get($id));
  }
  function update($params){
    $user = User::Get($params->id);
    $user->set_attributes($params);
    $result = $user->update();
    if ($result) {
      return array(204, null);
    } else {
      return array(400, $user->errors());
    }
  }
  function destroy($params){
    $user = User::Get($params->id);
    $result = $user->destroy();
    $result = $user->update();
    if ($result) {
      return array(204, null);
    } else {
      return array(400, $user->errors());
    }
  }
}