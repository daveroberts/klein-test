<?php

class UsersController{
  static function before($params, &$request, &$response){
    //$response->code(401);
    print_r($response); exit();
    return false;
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
  function before_show($id, $params){
    $response->code(401);
    return false;
  }
  function show($id, $params, &$request, &$response){
    $user = User::Get($id);
    if (!$user) { $response->code(400); }
    else {
      $response->code(200);
      $response->json($user);
    }
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