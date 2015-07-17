<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Gui\Communication\Table;

class TableConfiguration
{

    /**
     * @var
     */
    protected $url;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var
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
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * @todo Zed Translation in Template
     *
     * @param array $headers Provide php names for table columns
     *                       if you are goin to user Propel Query as data population
     */
    public function setHeaders(array $headers) {
        if ($this->isAssoc($headers) === true) {
            $this->headers = $headers;
        }
    }

    /**
     * @return array
     */
    public function getSortable() {
        return $this->sortableFields;
    }

    /**
     * @param array $sortable
     */
    public function setSortable(array $sortable) {
        $this->sortableFields = array_intersect(
            $sortable,
            array_keys($this->headers)
        );
    }

    /**
     * @return array
     */
    public function getSearchable() {
        return !empty($this->searchableFields) ? $this->searchableFields : array_keys($this->headers);
    }

    /**
     * @param array $searchable
     */
    public function setSearchable(array $searchable) {
        $this->searchableFields = $searchable;
    }

    /**
     * @return int
     */
    public function getPageLength() {
        return $this->pageLength;
    }

    /**
     * @param $length
     */
    public function setPageLength($length) {
        $this->pageLength = $length;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    private function isAssoc(array $array) {
        return array_keys($array) !== range(0, count($array) - 1);
    }

}
