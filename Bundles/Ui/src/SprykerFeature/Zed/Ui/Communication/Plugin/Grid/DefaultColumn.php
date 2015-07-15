<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Grid;

use SprykerFeature\Zed\Ui\Dependency\Plugin\AbstractGridPlugin;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class DefaultColumn extends AbstractGridPlugin
{

    const PARAM_FILTER = 'filter';
    const PARAM_SORT_DIRECTION = 'dir';
    const PARAM_SORT_COLUMN = 'sort';

    const COLUMN_NAME = 'name';
    const COLUMN_FILTERABLE = 'filterable';
    const COLUMN_SORTABLE = 'sortable';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $isFilterable = false;

    /**
     * @var bool
     */
    protected $isSortable = false;

    /**
     * @var bool
     */
    protected $defaultSortDirection;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return $this
     */
    public function filterable()
    {
        /*
         * @todo SprykerFeature_Zed_Library_Propel_Helper is missing.
         * need to be included again and used for filterable elements
         */
        //$this->isFilterable = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function unfilterable()
    {
        $this->isFilterable = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function sortable()
    {
        $this->isSortable = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function unsortable()
    {
        $this->isSortable = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function setDefaultSortAscending()
    {
        $this->defaultSortDirection = ModelCriteria::ASC;

        return $this;
    }

    /**
     * @return $this
     */
    public function setDefaultSortDescending()
    {
        $this->defaultSortDirection = ModelCriteria::DESC;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getData(array $data)
    {
        $data['columns'][$this->name] = $this->getColumnData();

        return $data;
    }

    /**
     * @param ModelCriteria $query
     *
     * @return ModelCriteria
     */
    public function specifyQuery(ModelCriteria $query)
    {
        $query = $this->applyFilter($query);
        $query = $this->applySort($query);

        return $query;
    }

    /**
     * @return array
     */
    protected function getColumnData()
    {
        $data = [];
        $data[self::COLUMN_NAME] = $this->name;
        $data[self::COLUMN_FILTERABLE] = $this->isFilterable;
        $data[self::COLUMN_SORTABLE] = $this->isSortable;

        return $data;
    }

    /**
     * @param ModelCriteria $query
     *
     * @return ModelCriteria
     */
    protected function applyFilter(ModelCriteria $query)
    {
        $filterValue = $this->getFilterValue();

        if ($this->isFilterable && $filterValue) {
            $qualifiedColumnName = (new \SprykerFeature_Zed_Library_Propel_Helper())
                                        ->getFullyQualifiedColumnName($query, $this->name);

            $query->where(
                $qualifiedColumnName . Criteria::LIKE . ' ?',
                $filterValue . '%',
                \PDO::PARAM_STR
            );
        }

        return $query;
    }

    /**
     * @return null|string
     */
    protected function getFilterValue()
    {
        return $this->getDedicatedRequestValue(self::PARAM_FILTER);
    }

    /**
     * @param ModelCriteria $query
     *
     * @return ModelCriteria
     */
    protected function applySort(ModelCriteria $query)
    {
        $sortDirection = $this->getSortDirection();

        if ($this->isSortable && $sortDirection) {
            $query->orderBy($this->name, $sortDirection);
        }

        return $query;
    }

    /**
     * @return null|string
     */
    protected function getSortDirection()
    {
        $requestedSortColumnName = $this->getRequestedSortColumnName();
        $requestedSortDirection = $this->getRequestedSortDirection();

        $sortDirection = null;
        if ($this->defaultSortDirection && !$requestedSortColumnName) {
            $sortDirection = $this->defaultSortDirection;
        }

        if ($requestedSortDirection && $requestedSortColumnName === $this->name) {
            $sortDirection = $requestedSortDirection;
        }

        return $sortDirection;
    }

    /**
     * @return null|string
     */
    protected function getRequestedSortColumnName()
    {
        $requestData = $this->getStateContainer()->getRequestData();

        if (isset($requestData[self::PARAM_SORT_COLUMN])) {
            return (string) $requestData[self::PARAM_SORT_COLUMN];
        }

        return;
    }

    /**
     * @return null|string
     */
    protected function getRequestedSortDirection()
    {
        $requestData = $this->getStateContainer()->getRequestData();

        if (isset($requestData[self::PARAM_SORT_DIRECTION])) {
            return (string) $requestData[self::PARAM_SORT_DIRECTION];
        }

        return;
    }

    /**
     * @param string $key
     *
     * @return null|string
     */
    protected function getDedicatedRequestValue($key)
    {
        $requestData = $this->getStateContainer()->getRequestData();

        if (isset($requestData[$key][$this->name])) {
            return (string) $requestData[$key][$this->name];
        }

        return;
    }

}
