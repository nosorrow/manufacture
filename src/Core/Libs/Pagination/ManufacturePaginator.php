<?php
/**
 * Created by PhpStorm.
 * User: plamenorama
 * Date: 5.4.2019 г.
 * Time: 19:08
 */

namespace Core\Libs\Pagination;

use JasonGrimes\Paginator;

class ManufacturePaginator extends Paginator
{
    public $ulClass = "pagination";
    public $liClass = "page-item";
    public $linkClass = "page-link";


    /**
     * Render an HTML pagination control.
     *
     * @return string
     */
    public function toHtml()
    {
        if ($this->numPages <= 1) {
            return '';
        }

        $html = '<ul class="' . $this->ulClass . '">';
        if ($this->getPrevUrl()) {
            $html .= '<li class="' . $this->liClass . '"><a class="' . $this->linkClass . '" href="' . htmlspecialchars($this->getPrevUrl()) . '">&laquo; '. $this->previousText .'</a></li>';
        }

        foreach ($this->getPages() as $page) {
            if ($page['url']) {
                $html .= '<li ' . ($page['isCurrent'] ? ' class="' . $this->liClass . ' active"' : 'class="' . $this->liClass.'"') . '><a class="' . $this->linkClass . '" href="' . htmlspecialchars($page['url']) . '">' . htmlspecialchars($page['num']) . '</a></li>';

            } else {
                $html .= '<li class="' .  $this->liClass . ' disabled"><span class="' . $this->linkClass . '">' . htmlspecialchars($page['num']) . '</span></li>';
            }
        }

        if ($this->getNextUrl()) {
            $html .= '<li class="' . $this->liClass . '" ><a class="' . $this->linkClass . '" href="' . htmlspecialchars($this->getNextUrl()) . '">'. $this->nextText .' &raquo;</a></li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * @param mixed $ulClass
     */
    public function setUlClass($ulClass)
    {
        $this->ulClass = $ulClass;
    }

    /**
     * @param mixed $liClass
     */
    public function setLiClass($liClass)
    {
        $this->liClass = $liClass;
    }

    /**
     * @param mixed $linkClass
     */
    public function setLinkClass($linkClass)
    {
        $this->linkClass = $linkClass;
    }


    public function __toString()
    {
        return $this->toHtml();
    }
}
