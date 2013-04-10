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
respond('GET', '/users/[i:id]', function($request, $response){
  echo '{"id":"'.$request->id.'","name":"Dave"}';
});
respond('PUT', '/users/[i:id]', function($request, $response){
  echo "update";
});

function json_resource($resource) {
  respond('GET', '/'.$resource, function($request, $response) use ($resource){
    if (response_type($request) != 'json') { return; }
    call_controller_action($resource, '_list', $request, $response);
  });
  respond('DELETE', '/users/[i:id]', function($request, $response) use ($resource){
    if (response_type($request) != 'json') { return; }
    call_controller_action($resource, 'destroy', $request, $response);
  });
  respond('POST', '/users', function($request, $response) use($resource){
    if (response_type($request) != 'json') { return; }
    call_controller_action($resource, 'create', $request, $response);
  });
}

function call_controller_action($resource, $action, &$request, &$response){
  $controller = ucfirst($resource).'Controller';
  $controller = new $controller();
  $action = $action.'_handler';
  return $controller->$action($request, $response);
}