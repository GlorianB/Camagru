<?php

class ImageController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION["loggedin"]))
            App::delay_redirect(0, "/camagru/login");
        $array = [];
        $data = [];
        $user = $this->load_model("user");
        //$user_comment = clone $user;
        $likes = $this->load_model('likes');
        $comments = $this->load_model("comment");
        $array = ORM::getInstance()->selectAll("image", array("user_id" => $_SESSION["id"]), array("creation_date", "DESC"));
        foreach ($array as $image)
        {
            foreach ($image as $key => $value)
                $image[$key] = htmlspecialchars($value);
            $user = ORM::getInstance()->select("user", ["user_id" => $image["user_id"]]);
            $comments = ORM::getInstance()->selectAll("comment", ["image_id" => $image["image_id"]]);
            $commentators = CommentModel::commentators($comments, new UserModel);
            $comment_section = '<div class="comments"><h2>Comments</h2>';
            foreach ($commentators as $commentator)
            $comment_section .= '<section><h3>' . $commentator["login"] . '</h3><p>' . $commentator["content"] . "</p></section>";
            $comment_section .= '</div>';
            $comment_section = htmlspecialchars($comment_section);
            $like_number = ORM::getInstance()->count("likes", ["image_id" => $image["image_id"]]);
            $liked = ORM::getInstance()->select("likes", ["image_id" => $image["image_id"], "user_id" => $image["user_id"]]);
            if ($liked instanceof LikeModel)
            $liked = "liked";
            else
            $liked = "not_liked";
            $data[$image["image_id"]] = array("name" =>$image["name"], "path" => $image["path"],
            "alt" => $image["description"], "date" => $image["creation_date"],
            "user" => $user->getLogin(), "user_id" => $user->getUserId(),
            "likes" => $like_number, "comments" => $comment_section, "liked" => $liked);
        }
        $data["icons"] = scandir("public/assets/icons/");
        unset($data["icons"][0], $data["icons"][1]);
        $this->load_view("image", $data);
    }
    
    public function delete($image_id, $image_title)
    {
        if (!isset($_SESSION["loggedin"]))
            App::route_error("access");
        $image = $this->load_model("image");
        $image = ORM::getInstance()->select("image", ["user_id" => $_SESSION["id"], "image_id" => $image_id]);
        if (!($image instanceof ImageModel))
            App::route_error("image"); 
        $this->load_view("delete", ["image_id" => htmlspecialchars($image_id), "image_title" => htmlspecialchars($image_title)]);
    }
    
    public function deleted($image_id)
    {
        if (!isset($_SESSION["loggedin"]))
            App::route_error("access");
        $image = $this->load_model("image");
        $image = ORM::getInstance()->select("image", ["user_id" => $_SESSION["id"], "image_id" => $image_id]);
        if (!($image instanceof ImageModel))
            App::route_error("image");
        ORM::getInstance()->delete("image", ["image_id" => $image_id]);
        echo "Image deleted<br><br>You will be redirected...";
        App::delay_redirect(5, "/camagru");
    }
    
    public function like($image_id)
    {
        if (!isset($_SESSION["loggedin"]))
            App::route_error("loggedin");
        $like = $this->load_model("likes");
        $liked = ORM::getInstance()->select("likes", ['user_id' => $_SESSION["id"], 'image_id' => $image_id]);
        if ($liked instanceof LikesModel)
        {
            ORM::getInstance()->delete("likes", ['user_id' => $_SESSION["id"], 'image_id' => $image_id]);
            echo "Unliked successfully!<br><br>You will be redirected...";
            App::delay_redirect(5, "/camagru");
        }
        else
        {
            ORM::getInstance()->insert("likes", ['user_id', 'image_id'], ["user_id" => $_SESSION["id"], "image_id" => $image_id]);
            echo "Liked successfully!<br><br>You will be redirected...";
            App::delay_redirect(5, "/camagru");
        } 
    }
    
    public function comment($image_id)
    {
        if (!isset($_SESSION["loggedin"]))
            App::route_error("loggedin");
        if (!isset($_POST["comment"]) || empty($_POST["comment"]))
            App::route_error("all_fields");
        $user = $this->load_model("user");
        $image = $this->load_model("image");
        $image = ORM::getInstance()->select("image", ["image_id" => $image_id]);
        $user = ORM::getInstance()->select("user", ["user_id" => $image->getUserId()]);
        $comment = $this->load_model("comment");
        $comment->setUserId($_SESSION["id"]);
        $comment->setImageId($image_id);
        $comment->setContent(htmlspecialchars(trim($_POST["comment"])));
        $comment->add($user);
    }

    public function upload()
    {
        if (!isset($_SESSION["loggedin"]))
            App::route_error("access");
        if (!isset($_FILES['myfile']))
            App::delay_redirect(0, "/camagru/image");
        $currentDir = "public";
        
        $uploadDirectory = "/assets/upload/";
    
        $errors = [];
    
        $fileExtensions = ['jpeg','jpg','png'];
    
        $fileName = $_FILES['myfile']['name'];
        $fileSize = $_FILES['myfile']['size'];
        $fileTmpName  = $_FILES['myfile']['tmp_name'];
        $fileType = $_FILES['myfile']['type'];
        $fileNameSep = explode('.', $fileName);
        $fileExtension = strtolower(end($fileNameSep));
    
        $uploadPath = $currentDir . $uploadDirectory . basename($fileName);
        
        if (isset($fileName)) {
    
            if (!in_array($fileExtension,$fileExtensions)) {
                $errors[] = "Wrong file type <br>";
            }
    
            if ($fileSize > 2000000) {
                $errors[] = "You cannot upload this file because its size exceeds the maximum limit of 2 MB. <br>";
            }
    
            if (empty($errors)) {
                $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
    
                if ($didUpload) {
                    return true;
                } else {
                    echo "An error occurred. <br>";
                    echo "You will be redirected...";
                    App::delay_redirect(5, "/camagru/image");
                }
            } else {
                foreach ($errors as $error) {
                    echo $error . "These are the errors <br>";
                    echo "You will be redirected...";
                    App::delay_redirect(5, "/camagru/image");
                }
            }
        }
    }

    public function save()
    {
        if (!isset($_SESSION["loggedin"]))
            App::route_error("access");
        if (!isset($_POST["img_name"], $_POST["img_description"]) || empty($_POST["img_name"]))
            App::route_error("all_fields_image");
        $image = $this->load_model("image");
        if (strlen($_POST["img_name"]) > 45)
        {
            echo "image name way too long !";
            App::delay_redirect(3, "/camagru/home");
            die;
        }
        $image->setName(htmlspecialchars(trim($_POST["img_name"])));
        $image->setPath(htmlspecialchars($_POST["img_path"]));
        $image->setDescription(htmlspecialchars(trim($_POST["img_description"])));
        $image2 = $_POST["img_path2"];
        $image->createImage($image2);
        echo "Your image has been added !<br><br> You will be redirected...";
        App::delay_redirect(0, "/camagru/home");
    }
}

?>