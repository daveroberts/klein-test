<?php

class UsersController{
  function before($params, &$request, &$response){
    return true;
  }
  function before_create($params, &$request, &$response){
    return (isset($_SESSION['current_user_id']));
  }
  function create($params, &$request, &$response){
    $user = User::Create($params);
    if (true){
      $response->code(201);
      $response->json($user);
    } else {
      $response->code(400);
    }
  }
  function _list($params, &$request, &$response){
    $response->code(200);
    $response->json(User::All());
  }
  function show($id, $params, &$request, &$response){
    $user = User::Get($id);
    if (!$user) { $response->code(400); }
    else {
      $response->code(200);
      $response->json($user);
    }
  }
  function before_update($id, $params, &$request, &$response){
    return (isset($_SESSION['current_user_id']));
  }
  function update($id, $params, &$request, &$response){
    $user = User::Get($id);
    $user->set_attributes($params);
    $result = $user->update();
    if ($result) {
      $response->code(204);
    } else {
      $response->code(400);
      $response->json($user->errors());
    }
  }
  function before_destroy($id, $params, &$request, &$response){
    return (isset($_SESSION['current_user_id']));
  }
  function destroy($id, $params, &$request, &$response){
    $user = User::Get($id);
    if (!$user) { $response->code(400); return; }
    $result = $user->destroy();
    if ($result) {
      $response->code(204);
    } else {
      $response->code(400);
      $response->json($user->errors());
    }
  }
}