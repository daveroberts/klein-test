<?php
require 'klein.php';
require_once('routes_helper.php');
require_all("controllers/*.php");
require_all("models/*.php");

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