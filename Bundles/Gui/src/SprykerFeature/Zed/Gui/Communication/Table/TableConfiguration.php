<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Gui\Communication\Table;

class TableConfiguration
{

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    private $header;

    /**
     * @var int
     */
    private $pageLength;

    /**
     * @var array
     */
    private $searchableFields;

    /**
     * @var array
     */
    private $sortableFields;

    /**
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @todo Zed Translation in Template
     *
     * @param array $header
     */
    public function setHeader(array $header)
    {
        if ($this->isAssoc($header) === true) {
            $this->header = $header;
        }
    }

    /**
     * @return array
     */
    public function getSortable()
    {
        return $this->sortableFields;
    }

    /**
     * @param array $sortable
     */
    public function setSortable(array $sortable)
    {
        $this->sortableFields = array_intersect($sortable, array_keys($this->header));
    }

    /**
     * @return array
     */
    public function getSearchable()
    {
        return !empty($this->searchableFields) ? $this->searchableFields : array_keys($this->header);
    }

    /**
     * @param array $searchable
     */
    public function setSearchable(array $searchable)
    {
        $this->searchableFields = $searchable;
    }

    /**
     * @return int
     */
    public function getPageLength()
    {
        return $this->pageLength;
    }

    /**
     * @param int $length
     */
    public function setPageLength($length)
    {
        $this->pageLength = $length;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    private function isAssoc(array $array)
    {
        return (array_values($array) !== $array);
    }

}
