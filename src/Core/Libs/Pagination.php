<?php
/*
 * $pagination->total($this->bookingModel->table('nm')->count());
 * $pagination->url_pattern(site_url('test').'/page/(:num)');
 * $link = $pagination->paginate(50);
 * echo $link;
 *
 */
namespace Core\Libs;

use Core\Libs\Pagination\ManufacturePaginator;

/**
 * Class Paginator
 * @package Core\Libs
 */
class Pagination
{
    public $limit;
    protected $totalItems;
    protected $itemsPerPage = 10;
    public $currentPage;
    protected $urlPattern;
    protected $maxPagesToShow = 10;
    protected $previousText = '';
    protected $nextText = '';

    /**
     * Paginator constructor.
     */
    public function __construct()
    {
        $currentPage = request_get('page') ? request_get('page') :1;
        $this->currentPage = (int)$currentPage;
        $this->setLimit($this->itemsPerPage);
    }

    /**
     * @param null $n
     * @return ManufacturePaginator
     */
    public function paginate($n = null)
    {
        if ($n !== null) {
            $this->per_page($n);
        }
        $paginator = new ManufacturePaginator($this->totalItems, $this->itemsPerPage,
                                    $this->currentPage, $this->urlPattern);

        $paginator->setNextText($this->nextText);
        $paginator->setPreviousText($this->previousText);
        $paginator->setMaxPagesToShow($this->maxPagesToShow);

        return $paginator;
    }

    /**
     * @param mixed $totalItems
     */
    public function total($totalItems)
    {
        $this->totalItems = $totalItems;
    }

    /**
     * @param mixed $itemsPerPage
     */
    public function per_page($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;

        $this->setLimit($itemsPerPage);
    }

    /**
     * @param mixed $currentPage
     */
    public function current_page($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @param mixed $urlPattern
     */
    public function url_pattern($urlPattern)
    {
        $this->urlPattern = $urlPattern;
    }

    /**
     * @param int $maxPagesToShow
     */
    public function setMaxPagesToShow($maxPagesToShow)
    {
        if ($maxPagesToShow < 3) {
            throw new \InvalidArgumentException('maxPagesToShow cannot be less than 3.');
        }
        $this->maxPagesToShow = $maxPagesToShow;
    }

    /**
     * @param string $previousText
     */
    public function setPreviousText($previousText)
    {
        $this->previousText = $previousText;
    }

    /**
     * @param string $nextText
     */
    public function setNextText($nextText)
    {
        $this->nextText = $nextText;
    }

    /**
     * @param $itemsPerPage
     */
    protected function setLimit($itemsPerPage)
    {
        $offset = ($this->currentPage * $itemsPerPage) - $itemsPerPage;

        $this->limit = " LIMIT " . (int)$itemsPerPage . ' OFFSET ' . (int)$offset;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }
}
