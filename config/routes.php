<?php

respond(function ($request, $response) {
  switch (response_type($request)) {
    case 'json':
      if (getallheaders()['X-XSRF-TOKEN'] != generateSecureCookie()){
        // 401 unauthorized
      }
      $response->header('Content-type: application/json');
      break;
    case 'xml':
      $response->header('Content-type: application/xml');
      break;
    case 'html':
      setcookie('XSRF-TOKEN', generateSecureCookie());
      $response->layout('views/layout.phtml');
      break;
  }
});

json_resource("users");

respond('GET', '/users', function($request, $response){
	if (response_type($request) == 'html') {
    $response->render('views/users.phtml', array("title"=>"Users Page"));
	}
});

function json_resource($resource) {
  respond('POST', '/users', function($request, $response) use($resource){
    if (response_type($request) != 'json') { return; }
    call_controller_action($resource, 'create', $request, $response);
  });
  respond('GET', '/'.$resource, function($request, $response) use ($resource){
    if (response_type($request) != 'json') { return; }
    call_controller_action($resource, '_list', $request, $response);
  });
  respond('DELETE', '/users/[i:id]', function($request, $response) use ($resource){
    if (response_type($request) != 'json') { return; }
    call_controller_action($resource, 'destroy', $request, $response);
  });
  respond('PUT', '/users/[i:id]', function($request, $response) use($resource){
	  if (response_type($request) != 'json') { return; }
    call_controller_action($resource, 'update', $request, $response);
	});
  respond('GET', '/users/[i:id]', function($request, $response){
    if (response_type($request) != 'json') { return; }
    call_controller_action($resource, 'show', $request, $response);
  });
}

function call_controller_action($resource, $action, &$request, &$response){
  $controller = ucfirst($resource).'Controller';
  $controller = new $controller();
  $params = json_decode($request->body());
  $bunch = null;
  if ($action == 'show' || $action == 'destroy' || $action == 'update'){
    $bunch = $controller->$action($request->id, $params);
  } else {
    $bunch = $controller->$action($params);
  }
  $result = $bunch[0];
  $body = $bunch[1];
  if ($result){
    $code = 200;
    if ($action == 'destroy'){ $code = 204; }
    if ($action == 'create'){ $code = 201; }
    if ($action == 'update'){ $code = 204; }
    $response->code($code);
  } else {
    $response->code(400);
  }
  if ($body){ $response->json($body); }
}