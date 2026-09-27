<?php


const DB_NAME = 'retailsmart';
const DB_USER = 'your_db_user';
const DB_PASS = 'your_db_password';

const APP_NAME = 'RetailSmart';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
