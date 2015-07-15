<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Business\Grid\StateContainer;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\Collection;

class StateContainer implements StateContainerInterface
{

    /**
     * @var array
     */
    protected $requestData;

    /**
     * @var Criteria
     */
    protected $baseQuery;

    /**
     * @var Criteria
     */
    protected $specifiedQuery;

    /**
     * @var Criteria
     */
    protected $trimmedQuery;

    /**
     * @var Collection
     */
    protected $queryResult;

    /**
     * @param array $requestData
     * @param Criteria $baseQuery
     */
    public function __construct(array $requestData, Criteria $baseQuery)
    {
        $this->baseQuery = $baseQuery;
        $this->requestData = $requestData;
    }

    /**
     * @return Criteria
     */
    public function getBaseQuery()
    {
        return $this->baseQuery;
    }

    /**
     * @param Criteria $baseQuery
     */
    public function setBaseQuery(Criteria $baseQuery)
    {
        $this->baseQuery = $baseQuery;
    }

    /**
     * @return Collection
     */
    public function getQueryResult()
    {
        return $this->queryResult;
    }

    /**
     * @param Collection $queryResult
     */
    public function setQueryResult(Collection $queryResult)
    {
        $this->queryResult = $queryResult;
    }

    /**
     * @return array
     */
    public function getRequestData()
    {
        return $this->requestData;
    }

    /**
     * @param array $requestData
     */
    public function setRequestData(array $requestData)
    {
        $this->requestData = $requestData;
    }

    /**
     * @return Criteria
     */
    public function getSpecifiedQuery()
    {
        return $this->specifiedQuery;
    }

    /**
     * @param Criteria $specifiedQuery
     *
     * @return mixed|void
     */
    public function setSpecifiedQuery(Criteria $specifiedQuery)
    {
        $this->specifiedQuery = $specifiedQuery;
    }

    /**
     * @return Criteria
     */
    public function getTerminatedQuery()
    {
        return $this->trimmedQuery;
    }

    /**
     * @param Criteria $trimmedQuery
     */
    public function setTerminatedQuery(Criteria $trimmedQuery)
    {
        $this->trimmedQuery = $trimmedQuery;
    }

}
