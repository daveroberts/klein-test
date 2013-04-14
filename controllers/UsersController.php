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
  function update($id, $params){
    $user = User::Get($id);
    $user->set_attributes($params);
    $result = $user->update();
    if ($result) {
      return array(204, null);
    } else {
      return array(400, $user->errors());
    }
  }
  function destroy($id, $params){
    $user = User::Get($id);
    $result = $user->destroy();
    if ($result) {
      return array(204, null);
    } else {
      return array(400, $user->errors());
    }
  }
}