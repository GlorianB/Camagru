<?php

class LoginController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION["loggedin"]))
            $this->load_view("login");
        else
            App::route_error("loggedin");
    }

    public function authenticate()
    {
        if (!isset($_SESSION["loggedin"]))
        {
            if (!isset($_POST["username"], $_POST["password"]) || empty($_POST['username']) || empty($_POST['password']))
                App::route_error("both_fields");
            $user = $this->load_model("user");
            $user = ORM::getInstance()->select("user", ["login" => $_POST["username"]]);
            UserModel::login($user);
        }
        App::route_error("loggedin");
    }

    public function logout()
    {
        session_destroy();
        App::redirect("/camagru/home");
    }
}

?>