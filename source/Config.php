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
    "facebook_appId" => "2471406043121441",
    "twitter_creator" => "@cardoso_welton",
    "twitter_site" => "@cardoso_welton"
]);

define("MAIL", [
    "host" => "smtp.sendgrid.net",
    "port" => "587",
    "user" => "apikey",
    "passwd" => "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",
    "from_name" => " Welton V. Cardoso",
    "from_email" => "weltonvianacardoso@gmail.com"
]);

define("FACEBOOK_LOGIN", [
    'clientId'          => "2471406043121441",
    'clientSecret'      => "c7d7501176ff4e4603caa559b32cc8a5",
    'redirectUri'       => SITE["root"]."/facebook",
    'graphApiVersion'   => "v6.0",
]);

define("GOOGLE_LOGIN", [
    "clientId" => "718353652537-c2hc8stn3iph08gi5buncnh7s3g05lmo.apps.googleusercontent.com",
    "clientSecret" => "a709wy-jHEUF5Yt5A4VS0bFQ",
    "redirectUri" => SITE["root"]."/google"
]);