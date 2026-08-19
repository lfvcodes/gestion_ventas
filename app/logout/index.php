<?php
#CODE_BY_LFVCODES
session_start();
require_once '../util/misc.php';
unset($_SESSION['pro']);
$_SESSION = array();
header("Location: ../login/");
