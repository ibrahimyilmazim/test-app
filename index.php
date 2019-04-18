<?php
if(!isset($_GET['page'])) { // eğer boşsa anapage varsayalım.
   $page = 'homepage';
} else {
   $page = $_GET['page'];
}


switch($page) {
case 'iletisim':
case 'login':
case 'register':
case 'forgot-password':
   include "pages/{$page}.php";
break;

default: // hiç birisi değilse 404 varsayalim
	include 'error/404.php';
}

?>