<?php


namespace Source\Controllers;


use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\FacebookUser;
use Source\Models\User;
use Source\Support\Email;

/**
 * Class Auth
 * @package Source\Controllers
 */
class Auth extends Controller {

    /**
     * Auth constructor.
     * @param $router
     */
    public function __construct($router) {
        parent::__construct($router);
    }

    /**
     * @param $data
     */
    public function login($data):void {
        $email = filter_var($data["email"], FILTER_VALIDATE_EMAIL);
        $passwd = filter_var($data["passwd"], FILTER_DEFAULT);

        if (!$email || !$passwd) {
            echo $this->ajaxResponse("message", [
               "type" => "alert",
               "message" => "Dados inválidos, preencha corretamente."
            ]);
            return;
        }

        $user = (new User())->find("email = :e", "e={$email}")->fetch();
        if (!$user || !password_verify($passwd, $user->passwd)) {
            echo $this->ajaxResponse("message", [
                "type" => "error",
                "message" => "Login ou Senha inválido!"
            ]);
            return;
        }

        $_SESSION["user"] = $user->id;
        echo $this->ajaxResponse("redirect", ["url" => $this->router->route("app.home")]);
    }

    /**
     * @param $data
     */
    public function register($data): void {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        if (in_array("", $data)) {
            echo $this->ajaxResponse("message", [
                "type" => "error",
                "message" => "Preencha todos os campos para cadastrar-se!"
            ]);
            return;
        }

        $user = new User();
        $user->first_name = $data["first_name"];
        $user->last_name = $data["last_name"];
        $user->email = $data["email"];
        $user->passwd = $data["passwd"];

        if (!$user->save()) {
            echo $this->ajaxResponse("message", [
               "type" => "error",
               "message" => $user->fail()->getMessage()
            ]);
            return;
        }

        $_SESSION["user"] = $user->id;
        echo $this->ajaxResponse("redirect", [
           "url" => $this->router->route("app.home")
        ]);
    }

    /**
     * @param $data
     */
    public function forget($data): void
    {
        $email = filter_var($data["email"], FILTER_VALIDATE_EMAIL);
        if (!$email){
            echo $this->ajaxResponse("message", [
                "type" => "alert",
                "message" => "Informe o SEU E-MAIL para recuperar a senha!"
            ]);
            return;
        }
        $user = (new User())->find("email = :e", "e={$email}")->fetch();
        if (!$user) {
        echo $this->ajaxResponse("message", [
            "type" => "error",
            "message" => "E-MAIL informado, não cadastrado!"
        ]);
        return;
    }
        $user->forget = (md5(uniqid(rand(), true)));
        $user->save();

        $_SESSION["forget"] = $user->id;

        $email = new Email();
        $email->add(
            "Recupere sua senha | ". site("name"),
            $this->view->render("emails/recover", [
              "user" => $user,
              "link" => $this->router->route("web.reset", [
                  "email"=> "$user->email",
                  "forget"=> "$user->forget"
              ])
            ]),
            "{$user->first_name} {$user->last_name}",
            $user->email
        )->send();
        flash("success", "Enviamos um link de recuperação para seu E-mail");
         echo $this->ajaxResponse("redirect", [
             "url" => $this->router->route("web.forget")
         ]);

    }

    /**
     * @param $data
     */
    public function reset($data): void
    {
     if (empty($_SESSION["forget"]) || $user = (new User())->findById($_SESSION["forget"])) {
         flash("info", "Nao foi possivel recuperar, tente novamente");
         echo $this->ajaxResponse("redirect", [
             "url" => $this->router->route("web.forget")
         ]);
         return;
        }
        if (empty($data["password"]) || empty($data["password_re"])) {
            echo $this->ajaxResponse("message", [
                "type" => "alert",
                "message" => "Informe e repita sua nova senha!"
            ]);
            return;
        }
        if ($data["password"] != $data["password_re"]) {
            echo $this->ajaxResponse("message", [
                "type" => "error",
                "message" => "voçâ informou senhas diferentes"
            ]);
            return;
        }
        $user->passwd = $data["password"];
        $user->forget = null;

        if (!$user->save()) {
            echo $this->ajaxResponse("message", [
                "type" => "error",
                "message" => $user->fail()->getMessage()
            ]);
            return;
        }

        unset($_SESSION["forget"]);

        flash("success","Sua senha foi redefinida com sucesso");
        echo $this->ajaxResponse("redirect", [
            "url" => $this->router->route("web.login")
        ]);
    }

    /**
     *
     */
    public function facebook(): void
    {
        $facebook = new Facebook(FACEBOOK_LOGIN);

        $error = filter_input(INPUT_GET, "error", FILTER_SANITIZE_STRIPPED);
        $code = filter_input(INPUT_GET, "code", FILTER_SANITIZE_STRIPPED);

        if (!$error && !$code){
            $auth_url = $facebook->getAuthorizationUrl(["scope" => "email"]);
            header("Location{[$auth_url}");
            return;

        }
        if ($error){
            flash("error","Não foi possível logar com o Facebook ");
            $this->router->redirect("web_login");
        }
        if ($code && empty($_SESSION["facebook_auth"])) {
            try {
                $token = $facebook->getAccessToken("authorization_code", ["code" =>$code]);
                $_SESSION["facebook_auth"] = serialize($facebook->getResourceOwner($token));
            } catch (\Exception $exception) {
                flash("error","Não foi possível logar com o Facebook ");
                $this->router->redirect("web_login");
            }
        }

        /** @var $facebook_user FacebookUser */
        $facebook_user = unserialize($_SESSION["facebook_auth"]);
        $user_by_id = (new User())->find("facebook_id = :id","id={$facebook_user->getId()}")->fetch();

//        LOGIN BY FACEBOOK
        if ($user_by_id) {
            unset($_SESSION["facebook_auth"]);
            $_SESSION["user"] = $user_by_id->id;
            $this->router->redirect("web_home");
        }

//        LOGIN BY E_MAIL
        $user_by_email = (new User())->find("email= :e","e={$facebook_user->getEmail()}")->fetch();
        if ($user_by_email) {
            flash("info","Olá {$facebook_user->getFirstName()}, faça login para conectar seu Facebook");
            $this->router->redirect("web_login");
        }

//        REGISTER IF NOT
        $link = $this->router->route("web_login");;
        flash(
            "info",
            "Olá {$facebook_user->getFirstName()},<b>se já tem conta clique em <a title='fazer login' href='{$link}'>FAZER LOGIN</a></b> ou complete seu cadastro");
        $this->router->redirect("web_register");
    }

}