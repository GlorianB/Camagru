<?php

class CommentModel
{
    private $comment_id;
    private $user_id;
    private $image_id;
    private $content;

    public function __construct() {}

    public function add(UserModel $user)
    {
        ORM::getInstance()->insert("comment", ["user_id", "image_id", "content"],
        ["user_id" => $this->getUserId(), "image_id" => $this->getImageId(), "content" => $this->getContent()]);
        echo "Comment added with success!";
        if ($user->getPreference() === "1")\
            mail($user->getEmail(), "New comment", "Your image has received a new comment ! Checkout on http://localhost:8080/camagru/");
        echo "<br><br>You will be redirected...";
        App::delay_redirect(5, "/camagru/home");
    }

    public static function commentators(array $comments, UserModel $user)
    {
        $result = array();
        foreach ($comments as $comment)
        {
            $user = ORM::getInstance()->select("user", ["user_id" => $comment["user_id"]]);
            $result[] = ["login" => $user->getLogin(), "content" => $comment["content"]];
        }
        return $result;
    }

    public function getCommentId()
    {
        return $this->comment_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getImageId()
    {
        return $this->image_id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setCommentId($comment)
    {
        $this->comment = $comment;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setImageId($image_id)
    {
        $this->image_id = $image_id;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }
}

?>