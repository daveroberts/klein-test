<?php

class JSONController{
	function _list_handler(&$request, &$response){
    $response->json($this->_list());
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
}