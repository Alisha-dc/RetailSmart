<?php

//Database settings
const DB_HOST='127.0.0.1';
const DB_NAME ='retailsmart';
const DB_USER='root';
const DB_PASS='';

//Application name

const APP_NAME='RetailSmart';

//Start session 
//Sessions are used for login and shopping cart.

if(session_status()==PHP_SESSION_NONE){
    session_start();
}


?>
