<?php
// Set Default TimeZone
date_default_timezone_set('America/New_York');

/** 
 * Class Autoloader
 * Essentially, this function will capture any calls
 * to a Class, and if it's one in our App, 
 * the file associated with that class is loaded
 * All classes use the 
 * app\** Namespace
 */
function appClassLoader( $class ){
  $class_file = __DIR__ . "/" . $class . ".php";
  $class_file = preg_replace("/\\\\/", "/", $class_file);
  if ( file_exists($class_file) ){
    require_once($class_file);
  }
}
// Register out autoloader function
spl_autoload_register( "appClassLoader" );
?>
