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

respond('POST', '/users', function($request, $response){
	$request->header('HTTP/1.0 201 Created', true, 201);
  $vars = json_decode(file_get_contents('php://input'));
  echo '{"id":"999","name":"'.$vars->name.'"}';
});
respond('GET', '/users/[i:id]', function($request, $response){
  echo '{"id":"'.$request->id.'","name":"Dave"}';
});
respond('PUT', '/users/[i:id]', function($request, $response){
  echo "update";
});
respond('DELETE', '/users/[i:id]', function($request, $response){
  echo "destroy";
});

function json_resource($resource) {
  respond('GET', '/'.$resource, function($request, $response) use ($resource){
    $controller = ucfirst($resource).'Controller';
    $action = '_list';
    $controller = new $controller();
    $data = array();
    $controller->__init($data);
    $controller->$action();
    if (response_type($request) == 'json') {
      echo json_encode($data[$resource]);
    }
  });
}