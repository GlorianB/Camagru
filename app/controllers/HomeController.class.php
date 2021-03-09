<?php

class HomeController extends Controller
{
    public function index($page_number = 1)
    {
        if (gettype($page_number) !== "integer")
        {
            if (is_numeric($page_number))
                $page_number = intval($page_number);
            else
                $page_number = 1;
        }
        $page = new Page;
        if ($page_number > $page->getTotalPage())
            $page_number = 1;
        $array = [];
        $data = [];
        $user = $this->load_model("user");
        $likes = $this->load_model('likes');
        $comments = $this->load_model("comment");
        $array = ORM::getInstance()->selectAll("image", array(), array("creation_date", "DESC"), array($page->getCalc($page_number), $page->getNumElem()));
        foreach ($array as $image)
        {
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
            $image["name"] = htmlspecialchars($image["name"]);
            $data[$image["image_id"]] = array("name" =>$image["name"], "path" => $image["path"],
                "alt" => htmlspecialchars($image["description"]), "date" => $image["creation_date"],
                "user" => $user->getLogin(), "user_id" => $user->getUserId(),
                "likes" => $like_number, "comments" => $comment_section, "liked" => $liked);
            $data["page"] = array("total_page" => $page->getCount(), "num_elem" => $page->getNumElem(), "page_number" => intval($page_number));
        }
        $this->load_view("home", $data);
    }
}

?>