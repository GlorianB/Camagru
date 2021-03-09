<?php

class ImageModel
{
    private $image_id;
    private $name;
    private $path;
    private $description;
    private $creation_date;
    private $user_id;

    public function __construct() {}

    public function add()
    {
        ORM::getInstance()->insert("image", ["name", "path", "description", "user_id"], ["name" => $this->getName(), "path" => $this->getPath(), "description" => $this->getDescription(), "user_id" => $_SESSION["id"]]);
    }

    public function copyTransparent($src)
    {

        $im = imagecreatetruecolor(400, 300); 
        // $src_ = imagecreatefromjpeg($src);
        $src_ = imagecreatefromstring($src);
        // Prepare alpha channel for transparent background
        $alpha_channel = imagecolorallocatealpha($im, 0, 0, 0, 127); 
        imagecolortransparent($im, $alpha_channel); 
        // Fill image
        imagefill($im, 0, 0, $alpha_channel); 
        // Copy from other
        imagecopy($im,$src_, 0, 0, 0, 0, 400, 300); 
        // Save transparency
        imagesavealpha($im,true); 
        // Save PNG
        // imagepng($im);
        return $im;
    }

    public function createImage($image2)
    {
        $dest_image = explode('data:image/png;base64,',$this->getPath())[1];
        $dest_image = base64_decode($dest_image);
        $paint = explode('data:image/png;base64,',$image2)[1];
        $paint = base64_decode($paint);

        $paint = imagecreatefromstring($paint);
        
        $dest_image = $this->copyTransparent($dest_image);

        imagecopyresampled($dest_image , $paint , 0, 0, 0, 0, 400, 300 , 400, 300);

        ob_start();
        imagejpeg($dest_image);
        $contents = ob_get_contents(); 
        ob_end_clean();
        $dataUri = "data:image/jpeg;base64," . base64_encode($contents);
        $this->setPath($dataUri);
        $name = $this->generateSaveName();
        $result = $this->base64_to_img($name);
        $this->setPath($result);
        $this->add();
    }

    public function base64_to_img($filename)
    {
        $ifp = fopen("public/assets/img/" . $filename, 'wb');
        $data = explode(",", $this->getPath());
        fwrite($ifp, base64_decode($data[1]));
        fclose($ifp);
        return ($filename);
    }
    public function generateSaveName()
    {
        $filename = $this->generate() . ".png";
        if (!file_exists("public/assets/img/" . $filename))
            return $filename;
        return generateSaveName();
    }

    public function generate()
    {
        $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $number = "0123456789";
        $path = [];
        $length = strlen($alphabet) - 1;
        for ($i=0; $i < 5; $i++) { 
            $n = rand(0, $length);
            $path[] = $alphabet[$n];
        }
        $length = strlen($number) - 1;
        for ($i=0; $i < 2; $i++) { 
            $n = rand(0, $length);
            $path[] = $number[$n];
        }
        return implode($path);
    }

    public function getImageId()
    {
        return $this->image_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCreationDate()
    {
        return $this->creation_date;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setImageId($image_id)
    {
        $this->image_id = $image_id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setCreationDate($creation_date)
    {
        $this->creation_date = $creation_date;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
}

?>