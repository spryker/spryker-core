<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Gui\Communication\Table;

use SprykerFeature\Zed\Gui\Communication\Table\TableOptionsInterface;
use SprykerFeature\Zed\Gui\Communication\Table\TableOptions;

class TableConfiguration
{

    /**
     * @var
     */
    protected $url;

    /**
     * @var array
     */
    private $header;

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

    private $tableOptions;

    public function __construct()
    {
        $this->tableOptions = new TableOptions();
    }

    /**
     * @return TableOptionsInterface
     */
    public function getTableOptions()
    {
        return $this->tableOptions;
    }

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
     * @param array $header Provide php names for table columns
     *                       if you are goin to user Propel Query as data population
     */
    public function setHeader(array $header)
    {
        if ($this->isAssoc($header)) {
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
     * @param array $arr
     *
     * @return bool
     */
    private function isAssoc(array $arr)
    {
        return (array_values($arr) !== $arr);
    }

}
