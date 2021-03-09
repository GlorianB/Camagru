<?php

class UserModel
{
    private $user_id;
    private $login;
    private $password;
    private $email;
    private $token;
    private $forgot_token;
    private $preference;

    public function __construct() {}

    public static function login($user)
    {
        if ($user instanceof UserModel)
        {
            if ($user->getToken() !== "activated")
                App::route_error("activate");
            if ($user->getForgotToken() !== NULL && $user->getForgotToken() !== "updated")
                App::route_error("update");
            if (password_verify($_POST["password"], $user->getPassword()))
            {
                session_regenerate_id();
                $_SESSION['loggedin'] = true;
		        $_SESSION['name'] = htmlspecialchars($_POST['username']);
                $_SESSION['id'] = $user->getUserId();
                App::redirect("/camagru/home");
            }
            App::route_error("password");
        }
        App::route_error("username");
    }

    public function register()
    {
        $error = [];
        $this->checklength();
        if (ORM::getInstance()->select("user", ["login" => $this->getLogin()]))
            $error[] = $this->getLogin() . ": This username already taken";
        else if (!preg_match('/[A-Za-z0-9]+/', $this->getLogin()))
            $error[] = $this->getLogin() . ': This username is not valid!';
        if (ORM::getInstance()->select("user", ["email" => $this->getEmail()]))
            $error[] = $this->getEmail() . ": This mail address is already taken";
        else if (!filter_var($this->getEmail(), FILTER_VALIDATE_EMAIL))
            $error[] = $this->getEmail() . ": This email is not valid";
        if (!preg_match('/^(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9]{2,})(?=.*[a-z]+).{7,}$/', $this->getPassword()))
            $error[] = 'Password must be at least 7 characters long and contain at least 1 uppercase, 2 digits and 1 special char!';
        if (!empty($error))
        {
            foreach ($error as $e)
            {
                echo "<li>" . $e . "</li>" . "<br>";
            }
            echo "<br>You will be redirected...";
            App::delay_redirect(3, "/camagru/register");
            exit;
        }
        $this->setToken(uniqid("activate-"));
        ORM::getInstance()->insert("user", ["login", "email", "password", "token"],
            ["login" => $this->getLogin(), "email" => $this->getEmail(), "password" => $this->cryptPassword($this->getPassword()), "token" => $this->getToken()]);
        mail($this->getEmail(), "Account activation", "Hi " . $this->login . ", click here to activate your account : http://localhost:8080/camagru/register/validation/" . $this->getToken());
        echo "A mail has been sent to you<br><br>You will be redirected...";
        App::delay_redirect(3, "/camagru/home");
    }

    public function checklength()
    {
        if (strlen($this->getLogin()) > 45 || strlen($this->getPassword()) > 255 || strlen($this->getEmail()) > 255)
        {
            echo "Way too long !";
            App::delay_redirect(3, "/camagru/home");
            die;
        }
    }

    public function modify_account()
    {
        $this->setForgotToken(uniqid("forgot-"));
        ORM::getInstance()->update("user",
            ["login" => $this->getLogin(), "email" => $this->getEmail(), "password" => $this->cryptPassword($this->getPassword()), "forgot_token" => $this->getForgotToken(), "preference" => strval($this->getPreference())], ["user_id" => $this->getUserId()]);
        mail($this->getEmail(), "Account update", "Hi " . $this->login . ", click here to update your account : http://localhost:8080/camagru/profile/validation/" . $this->getForgotToken());
        echo "A mail has been sent to you<br><br>You will be redirected...";
        App::delay_redirect(3, "/camagru/login/logout");
    }


    public function resetPassword()
    {
        $maj = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $alphabet = "abcdefghijklmnopqrstuvwxyz";
        $number = "0123456789";
        $special = "!@#$&*";
        $password = array();
        $length = strlen($alphabet) - 1;
        $n = rand(0, $length);
        $password[] = $maj[$n];
        for ($i=0; $i < 5; $i++) { 
            $n = rand(0, $length);
            $password[] = $alphabet[$n];
        }
        $length = strlen($number) - 1;
        for ($i=0; $i < 2; $i++) { 
            $n = rand(0, $length);
            $password[] = $number[$n];
        }
        $length = strlen($special) - 1;
        $n = rand(0, $length);
        $password[] = $special[$n];
        $this->setPassword(self::cryptPassword(implode($password)));
        ORM::getInstance()->update("user", ["password" => $this->password], ["user_id" => $this->user_id]);
        return implode($password);
    }


    public static function cryptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }


    public function getUserId()
    {
        return $this->user_id;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getForgotToken()
    {
        return $this->forgot_token;
    }

    public function getPreference()
    {
        return $this->preference;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setForgotToken($forgot_token)
    {
        $this->forgot_token = $forgot_token;
    }

    public function setPreference($preference)
    {
        $this->preference = $preference;
    }
}

?>