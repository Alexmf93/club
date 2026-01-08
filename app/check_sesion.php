<?php

function is_logged_in(){
    return isset($_SESSION['username']);
}

function require_login(){
    if(!is_logged_in()){
        header('Location: login.php');
        exit();
    }
}

