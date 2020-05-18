<?php

namespace Core\Libs\Images;


/**
 * Class File
 * Getting file and return GD library resource
 */
class File
{

    /**
     * Source image link resource.
     * @var
     */
    public $src_image;
    /**
     * @var
     */
    public $src_image_type;
    /**
     * @var
     */
    public $src_image_info;

    /**
     * @var
     */
    public $save_image_path;


    /**
     * File constructor.
     */
    public function __construct()
    {
        if (!extension_loaded('gd') && !function_exists('gd_info')) {
            throw new Exception("GD Library extension not available with this PHP installation.");
        }
    }

    /**
     * @param $file
     * @return $this
     * @throws \Exception
     */
    public function get($file)
    {
        if (!file_exists($file)) {
            throw new \Exception(sprintf("File %s not found!", $file));
        }
        $img_info = getimagesize($file);

        switch ($img_info[2]) {
            case IMAGETYPE_PNG:
                $src_img = @imagecreatefrompng($file);
                break;

            case IMAGETYPE_JPEG:
                $src_img = @imagecreatefromjpeg($file);
                break;

            case IMAGETYPE_GIF:
                $src_img = @imagecreatefromgif($file);
                break;

            default:
                throw new \Exception(
                    "Only JPG, PNG & GIF file types are supported!"
                );
        }
        $this->save_image_path = $file;
        $this->src_image = $src_img;
        $this->src_image_info = $img_info;
        $this->src_image_type = exif_imagetype($file);

        return $this;

    }

}
