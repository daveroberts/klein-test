<?php

class JSONController{
	function _list_handler(&$request, &$response){
    $arr = $this->_list($request->id);
    if ($arr[0]){
      $response->code(200);
    } else {
      $response->code(400);
    }
    $response->json($arr[1]);
	}
  function destroy_handler(&$request, &$response){
    $arr = $this->destroy($request->id);
    if ($arr[0]){
      $response->code(204);
    } else {
      $response->code(400);
      $response->json($arr[1]);
    }
  }
  function create_handler(&$request, &$response){
    $arr = $this->create(json_decode($request->body()));
    if ($arr[0]){
      $response->code(201);
    } else {
      $response->code(400);
    }
    $response->json($arr[1]);
  }
  function update_handler(&$request, &$response){
    $arr = $this->update($request->id);
    if ($arr[0]){
      $response->code(204);
    } else {
      $response->code(400);
    }
    $response->json($arr[1]);
  }
  function show_handler(&$request, &$response){
    $arr = $this->show($request->id);
    if ($arr[0]){
      $response->code(200);
    } else {
      $response->code(400);
    }
    $response->json($arr[1]);
  }
}