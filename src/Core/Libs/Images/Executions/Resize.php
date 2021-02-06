<?php

namespace Core\Libs\Images\Executions;

use Core\Libs\Exceptions\ImageException;
use Core\Libs\Images\Image;

class Resize extends AbstractExecutions
{
    /**
     * @param Image $image
     * @return resource
     * @throws ImageException
     */
    public function execute(Image $image)
    {
        [$width_orig, $height_orig] = $image->src_image_info;

        $count_args = count($this->arguments);

        if ($count_args > 2) {
            throw new ImageException("Too many arguments");
        }

        if ($count_args === 0) {
            throw new ImageException("No arguments !");
        }

        if ($count_args === 2) {
            [$width, $height] = $this->arguments;

        }
        // if one argument - calculate %
        if ($count_args === 1) {
            $percent = ($this->arguments[0]>0)?$this->arguments[0]/100:$this->arguments[0];
            $width = $width_orig * $percent;
            $height = $height_orig * $percent;
        }

        return $this->resize($image->src_image, $width_orig, $height_orig, $width, $height);
    }

    /**
     * @param $image
     * @param null $width
     * @param null $height
     * @param $width_orig
     * @param $height_orig
     * @return resource
     */
    protected function resize($image, $width_orig, $height_orig, $width = null, $height = null)
    {
        $ratio_orig = $width_orig / $height_orig;

        if ($height === null) {
            $height = $width / $ratio_orig;

        } elseif ($width === null) {
            $width = $height * $ratio_orig;
        }

        $dst_image = imagecreatetruecolor($width, $height);
        if (get_resource_type($image) === 'gd') {
            imagecopyresampled($dst_image, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        }

        return $dst_image;

    }

}
