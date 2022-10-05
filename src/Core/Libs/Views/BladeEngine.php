<?php

/**
 *  Laravel Blade template
 * @see https://github.com/jenssegers/blade
 * View се извикват в контролера:
 * With helpers
 * view('name', $data);
 * With method
 * @see ManufactureEngine::render()
 * $this->view->render('name', $data);
 */

namespace Core\Libs\Views;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

use Core\Libs\Interfaces\View as ViewInterface;
use Jenssegers\Blade\Blade;

/**
 * Class BladeEngine
 * @package Core\Libs\Views
 */
class BladeEngine implements ViewInterface
{
    private static $instance = null;

    private $template_engine_path = VIEW_DIR;

    private $cache = APP_STORAGE . 'views';

    public $_data = array();

    public $blade;


    private function __construct()
    {
        $this->blade = new Blade($this->template_engine_path, $this->cache);

    }

    /**
     * @param $name
     * @param array $data
     * @throws \Exception
     */
    public function render($name, $data = [])
    {
        try {
            $this->bladeEngineRender($name, $data);

        } catch (\Exception $e){
            throw new \Exception("Blade engine error: {$e->getMessage()}", 404);
        }

    }

    /**
     * Get stored errors in sessin _erros
     * @return Core\Libs\Support\MessageBag;
     */
    private function getErrors()
    {
        return errors();
    }

    /**
     * @param $file
     * @param array $data
     */
    protected function bladeEngineRender($file, $data = [])
    {

        if (!isset($errors)) {
            $data['errors'] = $this->getErrors();

        } else {
            session_delete('_errors');
        }

        echo $this->blade->make($file, $data);
    }

    /**
     * @return View|null
     */
    public static function blade()
    {
        return self::getInstance();
    }

    /**
     * View::blade()->directive('datetime', function ($expression) {
     *   return "<?php echo with({$expression})->format('Y-m-D h:i:s'); ?>";
     *  });
     *  dont forget clear:views
     * @param $directive
     * @param callable $expr
     */
    public function directive($directive, callable $expr)
    {
        $this->blade->compiler()->directive($directive, $expr);

    }

    /**
     * Singleton
     * @return BladeEngine
     */
    public static function getInstance()
    {
        if (self::$instance === null) {

            self::$instance = new self();
        }

        return self::$instance;
    }

}
