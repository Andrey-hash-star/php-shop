<?php

require_once('dbConnection.php');
require_once('function.php');

unset($_SESSION['loggetUser']);

header('Location: /');
