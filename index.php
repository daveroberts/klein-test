<?php
require 'vendor/SleepyPuppy/sleepypuppy.php';
$application_salt = 'mysalt-8fhgns9984sndsg984jdsg848jsdg';

require_once("vendor/Assetic/Autoloader.php");
\Assetic_Autoloader::register();

session_start();
require_once("config/database.php");

require_once("config/routes.php");
dispatch();