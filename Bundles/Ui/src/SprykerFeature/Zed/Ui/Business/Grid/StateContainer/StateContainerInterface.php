<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Business\Grid\StateContainer;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\Collection;

interface StateContainerInterface
{

    /**
     * @param array $requestData
     * @param Criteria $baseQuery
     */
    public function __construct(array $requestData, Criteria $baseQuery);

    /**
     * @return array $requestData
     */
    public function getRequestData();

    /**
     * @param array $requestData
     */
    public function setRequestData(array $requestData);

    /**
     * @return Criteria
     */
    public function getBaseQuery();

    /**
     * @param Criteria $baseQuery
     */
    public function setBaseQuery(Criteria $baseQuery);

    /**
     * @return Criteria
     */
    public function getSpecifiedQuery();

    /**
     * @param Criteria $specifiedQuery
     */
    public function setSpecifiedQuery(Criteria $specifiedQuery);

    /**
     * @return Criteria
     */
    public function getTerminatedQuery();

    /**
     * @param Criteria $trimmedQuery
     */
    public function setTerminatedQuery(Criteria $trimmedQuery);

    /**
     * @return Collection $queryResult
     */
    public function getQueryResult();

    /**
     * @param Collection $queryResult
     *
     * @return mixed
     */
    public function setQueryResult(Collection $queryResult);

}
