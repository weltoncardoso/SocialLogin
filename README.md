Criação de uma página de login em php:
 - Sistema MVC
 - Autenticação com Facebook, Google e E-mail

 - Ajustar em source/config conforme
 Email em sendgrid.net
 
 sociais em: https://packagist.org/packages/league/oauth2-facebook  https://packagist.org/packages/league/oauth2-google
 
 
define("MAIL", [
    "host" => "",
    "port" => "587",
    "user" => "",
    "passwd" => "",
    "from_name" => "",
    "from_email" => ""
]);

define("FACEBOOK_LOGIN", [
    'clientId'          => "",
    'clientSecret'      => "",
    'redirectUri'       => SITE["root"]."/facebook",
    'graphApiVersion'   => "",
]);

define("GOOGLE_LOGIN", [
    "clientId" => "",
    "clientSecret" => "",
    "redirectUri" => SITE["root"]."/google"
]);
