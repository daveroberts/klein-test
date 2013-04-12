<?php

respond(function ($request, $response) {
  switch (response_type($request)) {
    case 'json':
      $headers = getallheaders();
      $xsrf_token = null;
      if (isset($headers['X-XSRF-TOKEN'])){ $xsrf_token = $headers['X-XSRF-TOKEN']; }
      if ($xsrf_token != generateSecureCookie()){
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

respond('GET', '/current_user', function($request, $response){
  if (isset($_SESSION['current_user_id'])) { echo $_SESSION['current_user_id']; }
});

respond('POST', '/login', function($request, $response){
  $params = json_decode($request->body());
  if ($params->username == 'admin' && $params->password == 'password'){
    $_SESSION['current_user_id'] = 'admin';
    $response->code(204);
  } else {
    $response->code(401);
  }
});

respond('POST', '/logout', function($request, $response){
  unset($_SESSION['current_user_id']);
  $response->code(204);
});

respond('GET', '/secure', function($request, $response){
	if (response_type($request) != 'html') { return; }
  $current_user_id = 'None';
  if (isset($_SESSION['current_user_id'])){ $current_user_id = $_SESSION['current_user_id']; }
  echo 'Current User ID: '.$current_user_id;
  print_r($_SESSION);
});

json_resource("users");

respond('GET', '/users', function($request, $response){
	if (response_type($request) == 'html') {
    $response->render('views/users.phtml', array("title"=>"Users Page"));
	}
});

function json_resource($resource) {
  respond('POST', '/users', function($request, $response) use($resource){
    call_controller_action($resource, 'create', $request, $response);
  });
  respond('GET', '/'.$resource, function($request, $response) use ($resource){
    call_controller_action($resource, '_list', $request, $response);
  });
  respond('DELETE', '/users/[i:id]', function($request, $response) use ($resource){
    call_controller_action($resource, 'destroy', $request, $response);
  });
  respond('PUT', '/users/[i:id]', function($request, $response) use($resource){
    call_controller_action($resource, 'update', $request, $response);
	});
  respond('GET', '/users/[i:id]', function($request, $response){
    call_controller_action($resource, 'show', $request, $response);
  });
}

function call_controller_action($resource, $action, &$request, &$response){
  $controller = ucfirst($resource).'Controller';
  $controller = new $controller();
  $params = json_decode($request->body());
  $bunch = $controller->$action($params);
  $code = $bunch[0];
  $body = $bunch[1];
  $response->code($code);
  if ($body){
    if (response_type($request) == 'json') { $response->json($body); }
    if (response_type($request) == 'xml') { /*$response->xml($body);*/ }
  }
}