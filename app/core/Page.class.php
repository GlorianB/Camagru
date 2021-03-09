<?php

class Page
{
    private $count;
    private $num_elem = 5;
    private $page = 1;
    private $total_page;

    public function __construct()
    {
        $this->count = intval(ORM::getInstance()->count("image", array()));
        $this->total_page = $this->count > 5 ? ceil($this->count / $this->num_elem) : 1;
    }

    public function getCalc($page_number)
    {
        return (($page_number - 1) * $this->num_elem);
    }

    public function getCount()
    {
        return $this->count;
    }
    
    public function getNumElem()
    {
        return $this->num_elem;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getTotalPage()
    {
        return $this->total_page;
    }

    public function setCount($count)
    {
        $this->count = $count;
    }

    public function setNumElem($num_elem)
    {
        $this->num_elem = $num_elem;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function setTotalPage($total_page)
    {
        $this->total_page = $total_page;
    }
}

?>