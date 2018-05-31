<?php
session_start();
require_once(__DIR__ . '/autoload.php');

use app\App;
use app\util\Text;

$app = App::app();
$app->run();
?>
