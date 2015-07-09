<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Grid;

use SprykerFeature\Zed\Ui\Dependency\Plugin\AbstractGridPlugin;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class Pagination extends AbstractGridPlugin
{

    const PARAM_PAGE = 'page';
    const PARAM_LIMIT = 'items';

    const DATA_DEFINITION_PAGE = 'page';
    const DATA_DEFINITION_PAGES = 'pages';

    /**
     * @var int
     */
    protected $defaultLimit = 30;

    /**
     * @var int
     */
    protected $defaultPageNumber = 1;

    /**
     * @param ModelCriteria $query
     *
     * @return ModelCriteria
     */
    public function terminateQuery(ModelCriteria $query)
    {
        $query->limit($this->getLimit());
        $query->offset($this->calculateOffset());

        return $query;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getData(array $data)
    {
        $data[self::DATA_DEFINITION_PAGES] = $this->calculatePagesCount();
        $data[self::DATA_DEFINITION_PAGE] = $this->getPageNumber();

        return $data;
    }

    /**
     * @return int
     */
    protected function getLimit()
    {
        $limit = $this->defaultLimit;

        $requestedLimit = $this->getRequestedLimit();

        if ($requestedLimit) {
            $limit = $requestedLimit;
        }

        return $limit;
    }

    /**
     * @return int
     */
    protected function calculateOffset()
    {
        return (int) ($this->getPageNumber() * $this->getLimit()) - $this->getLimit();
    }

    /**
     * @return int
     */
    protected function getPageNumber()
    {
        $pageNumber = $this->defaultPageNumber;

        $requestedPageNumber = $this->getRequestPageNumber();

        if ($requestedPageNumber) {
            $pageNumber = $requestedPageNumber;
        }

        return $pageNumber;
    }

    /**
     * @return int|null
     */
    protected function getRequestPageNumber()
    {
        $requestData = $this->getStateContainer()->getRequestData();

        if (isset($requestData[self::PARAM_PAGE])) {
            return (int) $requestData[self::PARAM_PAGE];
        }

        return;
    }

    /**
     * @return int|null
     */
    protected function getRequestedLimit()
    {
        $requestData = $this->getStateContainer()->getRequestData();

        if (isset($requestData[self::PARAM_LIMIT])) {
            return (int) $requestData[self::PARAM_LIMIT];
        }

        return;
    }

    /**
     * @return int
     */
    protected function calculatePagesCount()
    {
        $specifiedQuery = $this->getStateContainer()->getSpecifiedQuery();

        return (int) ceil($specifiedQuery->count() / $this->getLimit());
    }

}
