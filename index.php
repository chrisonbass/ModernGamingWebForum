<?php
session_start();
require_once(__DIR__ . '/autoload.php');

use app\App;
use app\util\Text;
use app\base\View;
use app\view\Table;

$app = new App();
$app->run();
?>
