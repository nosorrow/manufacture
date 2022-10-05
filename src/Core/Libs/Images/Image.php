<?php

namespace Core\Libs\Images;
/*
 * Simple Images resize
 *
 * Usage:
 *
 * $image = new Images();
 * $image->get('image.jpg')->resize(300,200)->withPreffix('resize_');
 * $image->pixelate(12)->save();
 * or
 * $image->save('dir/sub-dir/new.jpg);
 * or
 * $image->get('image.jpg')->resize(300,200)->move('images/');
 *
 */

use Core\Libs\Exceptions\ImageException;

/**
 * Class Images
 *
 * @method \Executions pixelate(int $size = 5)
 * @method \Executions resize(int $width = null, int $height = null)
 *
 */

class Image extends File
{
    /**
     * Destination image link resource.
     * @var
     */
    public $dst_image;
    /**
     * @var
     */
    public $image_preffix_name;
    /**
     * @var int
     */
    public $quality = 100;

    /**
     * @var array
     */
    public $manipulated_image_info = [];

    /**
     * Resize constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function __call($name, $arguments = null)
    {
        if (is_resource($this->dst_image)) {
            $this->src_image = $this->dst_image;
        }
        $class_name = $this->getClass($name);

        $class = new \ReflectionClass($class_name);

        $instance = $class->newInstance($arguments);

        $reflectionMethod = new \ReflectionMethod($class_name, 'execute');
        $this->dst_image = $reflectionMethod->invoke($instance, $this);

        return $this;
    }

    /**
     * @param $class
     * @return string
     * @throws \Exception
     */
    public function getClass($class)
    {
        $classname = sprintf(
            'Core\Libs\Images\Executions\\%s',
            ucfirst($class)
        );

        if (class_exists($classname)) {
            return $classname;
        }

        throw new \Exception('Commands: ' . $classname . ' is not available');
    }

    /**
     * @param $preffix
     * @return $this
     */
    public function withPreffix($preffix)
    {
        $this->image_preffix_name = $preffix;

        return $this;
    }

    public function quality($percent)
    {
        $this->quality = $percent;
        return $this;
    }

    /**
     * @param null $path
     * @return bool
     * @throws ImageException
     */
    public function save($path = null)
    {
        if ($path === null) {
            $path = $this->save_image_path;
        }
        if (!realpath(dirname($path))) {
            $dir = dirname($path);
            if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new \RuntimeException(
                    sprintf('Directory "%s" was not created', $dir)
                );
            }
        }

        if ($this->image_preffix_name) {
            $path =
                dirname($path) .
                '/' .
                $this->image_preffix_name .
                basename($path);
        }

        if (file_put_contents($path, $this->buffering()) === false) {
            throw new ImageException('Nothing to save !');
        }

        if (is_resource($this->dst_image)) {
            imagedestroy($this->dst_image);
        }
        if (is_resource($this->src_image)) {
            imagedestroy($this->src_image);
        }

        $this->setImageInfo($path);

        return true;
    }

    /**
     * @param $dir
     * @return bool
     * @throws \Core\Libs\Exceptions\ImageException
     */
    public function move($dir)
    {
        // ако $dir не завършва с '/'
        if (strrpos($dir, '/') !== strlen($dir) - 1) {
            $dir .= '/';
        }
        if (!realpath($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new \RuntimeException(
                sprintf('Directory "%s" was not created', $dir)
            );
        }
        $path = $dir . pathinfo($this->save_image_path)['basename'];

        $this->save($path);

        return true;
    }

    /**
     * @param $path
     */
    public function setImageInfo($path)
    {
        $_r = getimagesize($path);

        $image_info['file_path'] = $path;
        $image_info['width'] = $_r[0];
        $image_info['height'] = $_r[1];
        $image_info['html'] = $_r[3];
        $image_info['mime'] = $_r['mime'];

        $this->manipulated_image_info = $image_info;
    }

    /**
     * @param $name
     * @return mixed
     * @throws ImageException
     */
    public function imageinfo($name = null)
    {
        if ($name === null) {
            return $this->manipulated_image_info;
        }

        if (!$this->manipulated_image_info[$name]) {
            throw new ImageException('No valid response found');
        }
        return $this->manipulated_image_info[$name];
    }
    /*
     * -----------------------------------------------------
     *          Get data to save image
     * -----------------------------------------------------
     */
    protected function get_ob_jpg()
    {
        ob_start();
        imagejpeg($this->dst_image, null, $this->quality);
        $data = ob_get_contents();
        ob_end_clean();

        return $data;
    }

    protected function get_ob_png()
    {
        ob_start();
        imagealphablending($this->dst_image, false);
        imagesavealpha($this->dst_image, true);
        imagepng($this->dst_image, null, -1);
        $data = ob_get_contents();
        ob_end_clean();

        return $data;
    }

    protected function get_ob_gif()
    {
        ob_start();
        imagesavealpha($this->dst_image, true);
        imagegif($this->dst_image);
        $data = ob_get_contents();
        ob_end_clean();

        return $data;
    }

    /**
     * ob_get_contents of image for save in file
     */
    public function buffering()
    {
        switch ($this->src_image_type) {
            case IMAGETYPE_JPEG:
                $data = $this->get_ob_jpg();
                break;
            case IMAGETYPE_GIF:
                $data = $this->get_ob_gif();
                break;
            case IMAGETYPE_PNG:
                $data = $this->get_ob_png();
                break;
        }

        return $data;
    }
}
