<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Model;

use Generated\Shared\Transfer\ApiPaginationTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\CustomerApi\Business\Transfer\CustomerTransferMapperInterface;
use Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface;
use Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface;

class CustomerApi
{

    /**
     * @var \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface
     */
    protected $apiQueryContainer;

    /**
     * @var \Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CustomerApi\Business\Transfer\CustomerTransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @param \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface $apiQueryContainer
     * @param \Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CustomerApi\Business\Transfer\CustomerTransferMapperInterface $transferMapper
     */
    public function __construct(
        CustomerApiToApiInterface $apiQueryContainer,
        CustomerApiQueryContainerInterface $queryContainer,
        CustomerTransferMapperInterface $transferMapper
    ) {
        $this->apiQueryContainer = $apiQueryContainer;
        $this->queryContainer = $queryContainer;
        $this->transferMapper = $transferMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function get(ApiRequestTransfer $apiRequestTransfer)
    {
        return $apiRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function add(ApiRequestTransfer $apiRequestTransfer)
    {
        $customerTransfer = new CustomerTransfer();

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function update(ApiRequestTransfer $apiRequestTransfer)
    {
        $customerTransfer = new CustomerTransfer();

        return $apiRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return bool
     */
    public function delete(ApiRequestTransfer $apiRequestTransfer)
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer[]
     */
    public function find(ApiRequestTransfer $apiRequestTransfer)
    {
        $criteriaTransfer = new PropelQueryBuilderCriteriaTransfer();
        $criteriaRuleSet = $this->apiQueryContainer->createPropelQueryBuilderCriteriaFromJson(
            $apiRequestTransfer->getFilter()->getFilter()
        );

        $criteriaTransfer->setRuleSet($criteriaRuleSet);
        $query = $this->queryContainer->queryFind();
        $query = $this->apiQueryContainer->createQuery($query, $criteriaTransfer);

        $query = $this->ensureQuerySort($query, $apiRequestTransfer->getFilter()->getPagination());
        $query = $this->ensureQueryLimit($query, $apiRequestTransfer->getFilter()->getPagination());
        $query = $this->ensureQueryOffset($query, $apiRequestTransfer->getFilter()->getPagination());

        return $this->transferMapper->convertCustomerCollectionToArray($query->find());
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $apiPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function ensureQueryLimit(ModelCriteria $query, ApiPaginationTransfer $apiPaginationTransfer)
    {
        $limit = (int)$apiPaginationTransfer->getLimit();
        $query->setLimit($limit);

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $apiPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function ensureQueryOffset(ModelCriteria $query, ApiPaginationTransfer $apiPaginationTransfer)
    {
        $page = (int)$apiPaginationTransfer->getPage();
        $limit = (int)$apiPaginationTransfer->getLimit();

        $offset = ($page - 1) * $limit;
        if ($offset < 0) {
            $offset = 0;
        }

        $query->setOffset($offset);

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $apiPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function ensureQuerySort(ModelCriteria $query, ApiPaginationTransfer $apiPaginationTransfer)
    {
        $sortCollection = (array)$apiPaginationTransfer->getSort();

        foreach ($sortCollection as $column => $order) {
            if ($order === Criteria::ASC) {
                $query->addAscendingOrderByColumn($column);
            } else {
                $query->addDescendingOrderByColumn($column);
            }
        }

        return $query;
    }

}
