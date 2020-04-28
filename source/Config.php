<?php
define("SITE", [
    "name" => "Login",
    "desc" => "Página de login com Facebook, Google e E-mail",
    "domain" => "localhost",
    "locale" => "pt_BR",
    "root" => "http://localhost/login"
]);

if ($_SERVER["SERVER_NAME"] == "localhost") {
    require __DIR__ . "/Minify.php";
}

define("DATA_LAYER_CONFIG", [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "login",
    "username" => "root",
    "passwd" => "",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);

define("SOCIAL", [
    "facebook_page" => "WeltonCardoso.BSI",
    "facebook_author" => "WeltonCardoso.BSI",
    "facebook_appId" => "",
    "twitter_creator" => "@cardoso_welton",
    "twitter_site" => "@cardoso_welton"
]);

define("MAIL", [
    "host" => "",
    "port" => "587",
    "user" => "",
    "passwd" => "",
    "from_name" => " Welton V. Cardoso",
    "from_email" => "weltonvianacardoso@gmail.com"
]);

define("FACEBOOK_LOGIN", [
    'clientId'          => "",
    'clientSecret'      => "",
    'redirectUri'       => SITE["root"]."/facebook",
    'graphApiVersion'   => "v6.0",
]);

define("GOOGLE_LOGIN", [
    "clientId" => "",
    "clientSecret" => "",
    "redirectUri" => SITE["root"]."/google"
]);
