<?php
#CODE_BY_LFVCODES
ob_start();
session_start();
if (!isset($_SESSION) && !isset($_SESSION['pro']['usr'])){
    header("Location: ./app/login/");
}else{
    header("Location: ./app/inicio/");
}
?>