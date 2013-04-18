<?php

function json_resource($resource) {
  respond('POST', '/'.$resource, function($request, $response) use($resource){
    call_controller_action($resource, 'create', $request, $response);
  });
  respond('GET', '/'.$resource, function($request, $response) use ($resource){
    call_controller_action($resource, '_list', $request, $response);
  });
  respond('DELETE', '/'.$resource.'/[:id]', function($request, $response) use ($resource){
    call_controller_action($resource, 'destroy', $request, $response);
  });
  respond('PUT', '/'.$resource.'/[:id]', function($request, $response) use($resource){
    call_controller_action($resource, 'update', $request, $response);
	});
  respond('GET', '/'.$resource.'/[:id]', function($request, $response) use($resource){
    call_controller_action($resource, 'show', $request, $response);
  });
}

function call_controller_action($resource, $action, &$request, &$response){
  $controller = ucfirst($resource).'Controller';
  $controller = new $controller();
  $params = json_decode($request->body());
  $bunch = null;
  if ($action == 'show' || $action == 'update' || $action == 'destroy') {
    $bunch = $controller->$action($request->id, $params);
  } else {
    $bunch = $controller->$action($params);
  }
  $code = $bunch[0];
  $body = $bunch[1];
  $response->code($code);
  if ($body){
    if (response_type($request) == 'json') { $response->json($body); }
    if (response_type($request) == 'xml') { /*$response->xml($body);*/ }
  }
}