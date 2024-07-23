<?php

require_once 'DatabaseConnection.php';
require_once 'DatabaseConnection2.php';

// must require_once 'DatabaseConnection.php';

// new DatabaseConnection object list

$db = new DatabaseConnection('localhost', 'root', '', 'web_template');


// must require_once 'DatabaseConnection2.php';

// new DatabaseConnection2 object list

$db2 = new DatabaseConnection2('172.25.112.131, 1433\SQLEXPRESS', 'SA', 'SystemGroup2018', 'web_template');
// $db2 = new DatabaseConnection2('DESKTOP-TRJMO4S\SQLEXPRESS', 'web_template', 'SystemGroup2018', 'web_template');