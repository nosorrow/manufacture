<?php

/**
 * View се извикват в контролера:
 * With helpers
 * view('name', $data);
 * With method
 * @see ManufactureEngine::render()
 * $this->view->render('name', $data);
 * Set layout
 * @see ManufactureEngine::setLayout()
 * $this->view->setLayout('dashboard')->render('file', data);
 * setLayout('name')
 *
 */

namespace Core\Libs\Views;

use Core\Libs\Interfaces\View as ViewInterface;

/**
 * Class ManufactureEngine
 * @package Core\Libs\Views
 */
class ManufactureEngine implements ViewInterface
{
    private static $instance = null;

    private $template_engine_path = VIEW_DIR;

    private $layout = 'Views/layout/default.php';

    public $_data = array();

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
        $this->layout = 'Views/layout/' . $file . '.php';

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
     * @throws \Exception
     */
    protected function phpEngineRender($view_name, $data = [])
    {
        if(strpos($view_name, '.') !== false){
            $view_name = str_replace('.', DIRECTORY_SEPARATOR, $view_name);
        }

        $main = $this->template_engine_path . $view_name . '.php';

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
                session_delete('_errors');
            }

            if (is_readable($view)) {

                include_once $view;

            } else {

                throw new \Exception('# phpEngineRender error: ManufactureEngine can not load  [' . $view . ' ]', 404);
            }

            echo ob_get_clean();

        } else {

            throw new \Exception('# phpEngineRender say error : ManufactureEngine can not load [' . $main . ' ]', 404);
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
     * @return array
     */
    public function __get($key)
    {
        return $this->_data[$key];
    }

    public function e($data, $filter = null)
    {
        return esc($data, $filter);
    }

    /**
     * Singleton
     * @return ManufactureEngine
     */
    public static function getInstance()
    {
        if (self::$instance === null) {

            self::$instance = new self();
        }

        return self::$instance;
    }
}
