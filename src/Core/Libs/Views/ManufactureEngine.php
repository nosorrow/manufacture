<?php

namespace Core\Libs\Views;

use Core\Libs\Interfaces\View as ViewInterface;
use Illuminate\Contracts\Filesystem\FileExistsException;

/**
 * Class ManufactureEngine
 * @package Core\Libs\Views
 */
class ManufactureEngine implements ViewInterface
{
    private static ?ManufactureEngine $instance = null;

    private string $template_engine_path = VIEW_DIR;

    private string $layout = "Views/layout/default.php";

    public array $_data = [];

    /**
     * ManufactureEngine constructor.
     */
    private function __construct()
    {
    }

    /**
     * $this->view->setLayout('dashboard')->render('name', $data);
     * @param $file
     * @return $this
     */
    public function setLayout($file)
	{
        $this->layout = "Views/layout/" . $file . ".php";

        return $this;
    }

    /**
     * @param $view_name
     * @param array $data
     * @throws \Exception
     */
    public function render($view_name, $data = [])
    {
        $this->phpEngineRender($view_name, $data);
    }

	/**
	 * @param $view_name
	 * @param array $data
	 * @return int
	 * @throws FileExistsException
	 * @throws \ReflectionException
	 */
    protected function phpEngineRender($view_name, $data = [])
    {
        if (strpos($view_name, ".") !== false) {
            $view_name = str_replace(".", DIRECTORY_SEPARATOR, $view_name);
        }

        $main = $this->template_engine_path . $view_name . ".php";
        $view = APPLICATION_DIR . $this->layout;
        $this->_data = $data;

        if (is_readable($main)) {
            ob_start();
            extract($data);

            // превенция на колизия с приложения на по-стара версия
            // ако е сетната променлива с име errors:
            // няма да вземе класът messagebag от сесията _errors, но ще я забърше
            if (!isset($errors)) {
                $errors = $this->getErrors();
            } else {
                session_delete("_errors");
            }

            if (is_readable($view)) {
                include_once $view;
            } else {
                throw new FileExistsException("# phpEngineRender: ManufactureEngine can not load [$view]",404);
            }

            return print ob_get_clean();
        }

		throw new FileExistsException("# phpEngineRender: ManufactureEngine can not load [$main]",404);
    }

    /**
     * Get stored errors in sessin _erros
     * @return Core\Libs\Support\MessageBag;
     * @throws \ReflectionException
     */
    private function getErrors()
	{
        return errors();
    }

    /**
     * @return array
     */
    public function __get($key)
    {
        return $this->_data[$key];
    }

    public function e($data, $filter = null): string
	{
        return esc($data, $filter);
    }

    /**
     * Singleton
     * @return ManufactureEngine
     */
    public static function getInstance(): ?ManufactureEngine
	{
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
