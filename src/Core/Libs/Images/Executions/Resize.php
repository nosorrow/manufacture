<?php

namespace Core\Libs\Images\Executions;

use Core\Libs\Images\Image as Image;

class Resize extends AbstractExecutions
{

    public function execute(Image $image)
    {
        if (count($this->arguments) > 2) {
            throw new \Exception("Too many arguments");

        } elseif (count($this->arguments) == 2) {
            list($width, $height) = $this->arguments;

        } elseif (count($this->arguments) == 1){
            $width = $this->arguments[0];
            $height = null;

        } else {
            throw new \Exception("No arguments !");

        }

        list($width_orig, $height_orig) = $image->src_image_info;

        return $this->resize($image->src_image, $width, $height, $width_orig, $height_orig);
    }

    /**
     * @param $image
     * @param null $width
     * @param null $height
     * @param $width_orig
     * @param $height_orig
     * @return resource
     */
    protected function resize($image, $width = null, $height = null, $width_orig, $height_orig)
    {
        $ratio_orig = $width_orig / $height_orig;

        if ($height == null) {
            $height = $width / $ratio_orig;

        } elseif ($width == null) {
            $width = $height * $ratio_orig;
        }

        $dst_image = imagecreatetruecolor($width, $height);
        if (get_resource_type($image) == 'gd'){
            imagecopyresampled($dst_image, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        }

        return $dst_image;

    }

}
