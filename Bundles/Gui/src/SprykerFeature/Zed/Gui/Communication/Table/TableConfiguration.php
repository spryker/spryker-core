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
     * @var array
     */
    private $sortable;

    /**
     * @var
     */
    private $pageLength;

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @todo Zed Translation in Template
     * @param array $headers Provide php names for table columns
     * if you are goin to user Propel Query as data population
     */
    public function setHeaders(array $headers)
    {
        if ($this->isAssoc($headers) === true) {
            $this->headers = $headers;
        }
    }

    /**
     * @return array
     */
    public function getSortable()
    {
        return $this->sortable;
    }

    /**
     * @param array $sortable
     */
    public function setSortable(array $sortable)
    {
        $this->sortable = array_intersect(
            $sortable,
            array_keys($this->headers)
        );
    }

    /**
     * @return int
     */
    public function getPageLength()
    {
        return $this->pageLength;
    }

    /**
     * @param $length
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
        return array_keys($array) !== range(0, count($array) - 1);
    }

}
