<?php

class RegisterController extends Controller
{
    public function index()
    {
        if (isset($_SESSION["loggedin"]))
            App::route_error("loggedin");
        $this->load_view("register");
    }

    public function registration()
    {
        if (isset($_SESSION["loggedin"]))
            App::route_error("loggedin");
        if (!isset($_POST['username'], $_POST['password'], $_POST['email'])
            || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']))
            App::route_error("all_fields_register");
        $user = $this->load_model("user");
        $user->setLogin($_POST['username']);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $user->register($user);
    }

    public function validation($activate)
    {
        if (isset($_SESSION["loggedin"]))
            App::route_error("loggedin");
        $user = $this->load_model("user");
        $user = ORM::getInstance()->select("user", ["token" => $activate]);
        if ($user instanceof UserModel)
        {
            $msg = "<br><br>You will be redirected...";
            ORM::getInstance()->update("user", ["token" => "activated"], ["user_id" => $user->getUserId()]);
            echo "You're account has been successfully activated !" . $msg;
            App::delay_redirect(5, "/camagru/login");
        }
        else
        {
            echo "This account doesn't exist or is already activated" . $msg;
            App::delay_redirect(5, "/camagru/login");
        }
    }

}

?>