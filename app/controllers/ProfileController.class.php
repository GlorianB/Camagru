<?php

class ProfileController extends Controller
{
    public function index()
    {
        if (isset($_SESSION["loggedin"]))
        {
            $user = $this->load_model("user");
            $user = ORM::getInstance()->select("user", ["login" => $_SESSION["name"]]);
            $this->load_view("profile", ["email" => $user->getEmail(), "preference" => $user->getPreference()]);
        }
        else
            App::route_error("access");
    }

    public function modify()
    {
        if (!isset($_SESSION["loggedin"]))
            App::route_error("access");
        $user = $this->load_model("user");
        $user = ORM::getInstance()->select("user", ["user_id" => $_SESSION["id"]]);
        $this->load_view("modify", ["email" => $user->getEmail()]);
    }

    public function modified()
    {
        if (!isset($_SESSION["loggedin"]))
            App::route_error("access");
        if (isset($_POST["username"], $_POST["email"], $_POST["password"], $_POST["confirm-password"])
            && !empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["confirm-password"]))
        {
            if ($_POST["password"] !== $_POST["confirm-password"])
                App::route_error("confirm-password");
            if (!preg_match('/^(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9]{2,})(?=.*[a-z]+).{7,}$/', $_POST["password"]))
            {
                echo "Password must be at least 7 characters long and contain at least 1 uppercase, 2 digits and 1 special char!";
                echo "<br><br>You will be redirected...";
                App::delay_redirect(5, "/camagru/profile/modify");
                exit;
            }
            $user = $this->load_model("user");
            $user = ORM::getInstance()->select("user", ["user_id" => $_SESSION["id"]]);
            $user->setLogin(htmlspecialchars($_POST['username']));
            $user->setEmail(htmlspecialchars($_POST['email']));
            $user->setPassword(htmlspecialchars($_POST['password']));
            $preference = isset(($_POST["preference"])) && ($_POST["preference"]) === "On" ? 1 : 0;
            $user->setPreference($preference);
            $user->checklength();
            $user->modify_account();
        }
        App::route_error("all_fields_modify");
    }

    public function validation($forgot)
    {
        $msg = "<br><br>You will be redirected...";
        if (isset($_SESSION["loggedin"]))
            App::route_error("loggedin");
        $user = $this->load_model("user");
        $user = ORM::getInstance()->select("user", ["forgot_token" => $forgot]);
        if ($user instanceof UserModel)
        {
            ORM::getInstance()->update("user", ["forgot_token" => "updated"], ["user_id" => $user->getUserId()]);
            echo "You're account has been successfully updated !" . $msg;
            App::delay_redirect(5, "/camagru/login");
        }
        else
        {
            echo "This account doesn't exist or the link is wrong" . $msg;
            App::delay_redirect(5, "/camagru/login");
        }
    }
}

?>