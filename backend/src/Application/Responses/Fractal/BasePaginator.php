<?php

namespace Backend\Application\Responses\Fractal;


use League\Fractal\Pagination\PaginatorInterface;

class BasePaginator implements PaginatorInterface
{

    private $currentPage;
    private $count;
    private $totalCount;

    public function __construct(int $currentPage, int $count, int $totalCount)
    {
        $this->currentPage = $currentPage;

        $this->count = $count;

        $this->totalCount = $totalCount;
    }

    /**
     * Get the current page.
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Get the last page.
     *
     * @return int
     */
    public function getLastPage()
    {
        return (int)ceil($this->totalCount / $this->count);
    }

    /**
     * Get the total.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->totalCount;
    }

    /**
     * Get the count.
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Get the number per page.
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->count;
    }

    /**
     * Get the url for the given page.
     *
     * @param int $page
     *
     * @return string
     */
    public function getUrl($page)
    {
        return (string)$page;
    }
}