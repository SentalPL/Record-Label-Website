<?php
session_start();

require 'config.php';
require 'classes/ProvenUsefulFunctions.php';
require 'classes/Messages.php';
require 'classes/Bootstrap.php';
require 'classes/Controller.php';
require 'classes/Model.php';
require 'classes/MainOperations.php';

require 'controllers/news.php';
require 'controllers/music.php';
require 'controllers/contact.php';
require 'controllers/admin.php';
require 'controllers/user.php';

require 'models/news.php';
require 'models/music.php';
require 'models/contact.php';
require 'models/admin.php';
require 'models/user.php';

$bootstrap = new Bootstrap($_GET);
$controller = $bootstrap->createController();
if ($controller){
	$controller->executeAction();
}
?>