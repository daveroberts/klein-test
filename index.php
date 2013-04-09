<?php
require 'klein.php';
$application_salt = 'mysalt-8fhgns9984sndsg984jdsg848jsdg';

respond('*', function ($request, $response) {
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
      break;
  }
});

respond('GET', '/users', function($request, $response){
  switch (response_type($request)) {
    case 'json':
      $request->header('HTTP/1.0 201 Created', true, 201);
      echo '[{"id":"1","name":"Claudia"},{"id":"2","name":"Cynthia"}]';
      //$this->header('HTTP/1.0 400 Bad Request', true, 400);
      break;
    case 'html':
      $response->render('views/users.phtml');
      break;
  }
});
respond('POST', '/users', function($request, $response){
  $vars = json_decode($request->body());
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

dispatch();

function generateSecureCookie(){
  global $application_salt;
  if ($application_salt == ''){ throw new Exception("Must set an application salt"); }
  return md5($application_salt.md5(session_id()));
}

function response_type($request, $default = 'html'){
  if (strpos($request->header('ACCEPT'), 'application/json') !== false){ return 'json'; }
  if (strpos($request->header('ACCEPT'), 'text/html') !== false){ return 'html'; }
  if (strpos($request->header('ACCEPT'), 'application/xml') !== false){ return 'xml'; }
  return $default;
}