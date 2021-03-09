<?php

class Controller
{

    /**
     * @var style_href : path to style.css 
     */
    protected $style_href = "/camagru/public/styles/style.css";
    protected $js_img_href = "/camagru/public/js/image.js";
    protected $js_popup_href = "/camagru/public/js/popup.js";

    /**
     * Load model given in parameter
     * @var model : model to be loaded
     */
    protected function load_model($model)
    {
        require_once dirname(__DIR__) . "/models/" . ucfirst($model) . "Model.class.php";
        $model_name = $model . "Model";
        return new $model_name;
    }

    /**
     * Load view given in parameter
     * @var model : view to be loaded
     */
    protected function load_view($view, $data = [], $folder= "")
    {
        require_once  dirname(__DIR__) . "/views/" . $folder . "/" . $view . ".php";
    }

}

?>