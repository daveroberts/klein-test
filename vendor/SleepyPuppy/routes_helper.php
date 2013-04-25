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
  if (method_exists($controller, 'before')){
    $result = true;
    $result = $controller->before($params, $request, $response);
    if (!$result){ return; }
  }
  $before_action = 'before_'.$action;
  if ($before_action == 'before__list'){ $before_action = 'before_list'; }
  if (method_exists($controller, $before_action)){
    $result = true;
    if ($action == 'show' || $action == 'update' || $action == 'destroy') {
      $result = $controller->$before_action($request->id, $params, $request, $response);
    } else {
      $result = $controller->$before_action($params, $request, $response);
    }
    if (!$result){ return; }
  }
  if ($action == 'show' || $action == 'update' || $action == 'destroy') {
    $result = $controller->$action($request->id, $params, $request, $response);
  } else {
    $result = $controller->$action($params, $request, $response);
  }
}