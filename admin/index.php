<?php
require_once '../core/init.php';
if(!is_logged_in()){
header('Location:login.php');
}

include 'includes/headerAD.php';
include 'includes/navigationAD.php'

// session_destroy();
 ?>
 Adminstrator Homepage

 <?php include 'includes/footerAD.php' ?>
