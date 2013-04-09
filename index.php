<?php
require 'klein.php';
$application_salt = 'mysalt-8fhgns9984sndsg984jdsg848jsdg';

require_once("config/routes.php");
require_all("controllers/*.php");
require_all("models/*.php");

dispatch();

function require_all($pattern){
  foreach (glob($pattern) as $filename){
    require_once $filename;
  }
}

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