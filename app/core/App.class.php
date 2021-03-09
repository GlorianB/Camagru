<?php

class App
{

    /**
     * @var string $controller; controller to be used (default 'home')
     */
    protected $controller = "Home";

    /**
     * @var string $method; controller's method to be used (default 'index')
     */
    protected $method = "index";

    /**
     * @var array $params; method's param list
     */
    protected $params = [];

    public function __construct()
    {
        session_start();


        $url = $this->parseUrl();

        if (isset($url[0]) && file_exists(dirname(dirname(__DIR__) . '/controllers/' . ucfirst($url[0]) . "Controller.class.php")))
        {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }

        if (!file_exists('app/controllers/' . $this->controller . "Controller.class.php"))
        {
            self::urlNotFound();
            die();
        }

        require_once 'app/controllers/' . $this->controller . "Controller.class.php";
        $element_name = $this->controller . "Controller";
        $this->controller = new $element_name; 

        if (isset($url[1]) && method_exists($this->controller, $url[1]))
        {
            $this->method = $url[1];
            unset($url[1]);
        }
      
        if (!method_exists($this->controller, $this->method))
        {     
            self::urlNotFound();
            die();
        } 

        $this->params = $url ? array_values($url) : [];
        
        try {
            call_user_func_array([$this->controller, $this->method], $this->params);
        }
        catch (ArgumentCountError $e)
        {
            self::urlNotFound();
        }
    }

    public static function urlNotFound()
    {
        echo "Url not found<br><br>You will be redirected...";
        self::delay_redirect(5, "/camagru");
        exit;
    }
    
    public static function redirect($url)
    {
        header("Location: " . $url);
        exit;
    }
    
    public static function delay_redirect($refresh, $url)
    {
        header("Refresh:" . $refresh . "; url=" . $url);
        exit;
    }
    
    /** 
     * parse URL and return an array
     * $url[0] : controller; $url[1] : method; $url[2] : param(s);
     * 
     * Example:
     * 
     * $_GET['url'] = home/salut/sava
     * 
     * Result: Array ( [0] => home [1] => salut [2] => cava )
     */
    public function parseUrl()
    {
        if (isset($_GET['url']))
        return (explode("/", filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)));
    }
    public static function route_error(string $error)
    {
        $redirect_msg = "<br><br>You will be redirected...";
        if ($error === "username")
        {
            echo("Incorrect username" . $redirect_msg);
            App::delay_redirect(5, "/camagru/login");
        }
        elseif ($error === "password")
        {
            echo("Incorrect password" . $redirect_msg);
            App::delay_redirect(5, "/camagru/login");
        }
        elseif ($error === "access")
        {
            echo "You have to be logged in to access this page" . $redirect_msg;
            App::delay_redirect(5, "/camagru/home");
        }
        elseif ($error === "loggedin")
        {
            echo "You're already logged in" . $redirect_msg;
            App::delay_redirect(5, "/camagru/home");
        }
        elseif ($error === "wrong_login")
        {
            echo "wrong username or email" . $redirect_msg;
            App::delay_redirect(5, "/camagru/login");
        }
        elseif ($error === "both_fields")
        {
            echo "Please fill both fields !" . $redirect_msg;
            App::delay_redirect(5, "/camagru/login");
        }
        elseif ($error === "all_fields_modify")
        {
            echo "Please fill all fields !" . $redirect_msg;
            App::delay_redirect(5, "/camagru/profile/modify");
        }
        elseif ($error === "all_fields_register")
        {
            echo "Please fill all fields !" . $redirect_msg;
            App::delay_redirect(5, "/camagru/register");
        }
        elseif ($error === "all_fields_image")
        {
            echo "Please fill all fields !" . $redirect_msg;
            App::delay_redirect(5, "/camagru/image");
        }
        elseif ($error === "confirm-password")
        {
            echo "Password aren't corresponding !" . $redirect_msg;
            App::delay_redirect(5, "/camagru/profile/modify");
        }
        elseif ($error === "activate")
        {
            echo "Account not activated yet" . $redirect_msg;
            App::delay_redirect(5, "/camagru/home");
        }
        elseif ($error === "activate")
        {
            echo "Account not updated yet" . $redirect_msg;
            App::delay_redirect(5, "/camagru/home");
        }
        elseif ($error === "update")
        {
            echo "Account not updated yet" . $redirect_msg;
            App::delay_redirect(5, "/camagru/home");
        }
        elseif ($error === "image")
        {
            echo "This is not your image, you can't delete it" . $redirect_msg;
            App::delay_redirect(5, "/camagru/home");
        }
        exit;
    }
}

?>