<?php
function appClassLoader( $class ){
  $class_file = __DIR__ . "/" . $class . ".php";
  $class_file = preg_replace("/\\\\/", "/", $class_file);
  if ( file_exists($class_file) ){
    require_once($class_file);
  }
}
spl_autoload_register( "appClassLoader" );
?>
