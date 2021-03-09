<?php

class LikesModel
{
    private $like_id;
    private $user_id;
    private $image_id;

    public function __construct() {}

    public function getLikeId()
    {
        return $this->like_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getImageId()
    {
        return $this->image_id;
    }

    public function setLikeId($like_id)
    {
        $this->like_id = $like_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setImageId($image_id)
    {
        $this->image_id = $image_id;
    }
}

?>