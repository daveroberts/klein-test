<?php

json_resource("users");

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

respond('GET', '/users', function($request, $response){
	if (response_type($request) == 'html') {
    $response->render('views/users/users.phtml', array("title"=>"Users Page"));
	}
});