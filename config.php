<?php
    const MYSQL_USER = 'root';
    const MYSQL_PASS = '';
    const MYSQL_DB = 'tpe_web_2';
    const MYSQL_HOST = 'localhost';
    const JWT_KEY = 'MujiBust33';
    const JWT_EXP = 3600; // 1hs

    define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');