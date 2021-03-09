<?php

class ForgotController extends Controller
{
    public function index()
    {
        if (isset($_SESSION["loggedin"]))
            App::route_error("loggedin");
        $this->load_view("forgot");
    }

    public function reset()
    {
        if (isset($_SESSION["loggedin"]))
            App::route_error("loggedin");
        if (isset($_POST["username"]) && isset($_POST["email"]) && !empty($_POST["username"]) && !empty($_POST["email"]))
        {
            $user = $this->load_model("user");
            $user = ORM::getInstance()->select("user", ["login" => $_POST["username"], "email" => $_POST["email"]]);
            if ($user instanceof UserModel)
            {
                $login = $user->getLogin();
                $password = $user->resetPassword();
                if (mail($user->getEmail(), "Your new password", "Hi " . $login . " ! Your new password is: " . $password))
                {
                    echo "Your password has been reset and a mail has been sent to your email address<br><br>You will be redirected...";
                    App::delay_redirect(8, "/camagru/login");
                }
                exit("An issue has been encoutered");
            }
            App::route_error("wrong_login");
            }
        App::route_error("both_fields");
        }
}

?>