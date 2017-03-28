<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Model;

use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Spryker\Zed\Api\Business\Exception\EntityNotFoundException;
use Spryker\Zed\Api\Business\Model\ApiCollection;
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
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\CustomerApiTransfer
     */
    public function get($idCustomer, ApiFilterTransfer $apiFilterTransfer)
    {
        $customerData = (array)$this->queryContainer
            ->queryCustomerById($idCustomer, $apiFilterTransfer->getFields())
            ->findOne();

        if (!$customerData) {
            throw new EntityNotFoundException('Customer not found idCustomer: ' . $idCustomer);
        }

        return $this->transferMapper->convertCustomer($customerData);
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
     * @return \Spryker\Zed\Api\Business\Model\ApiCollectionInterface //TODO should return transfer
     */
    public function find(ApiRequestTransfer $apiRequestTransfer)
    {
        $criteriaRuleSet = $this->apiQueryContainer->createPropelQueryBuilderCriteriaFromJson(
            $apiRequestTransfer->getFilter()->getFilter()
        );
        $criteriaTransfer = new PropelQueryBuilderCriteriaTransfer();
        $criteriaTransfer->setRuleSet($criteriaRuleSet);

        $query = $this->queryContainer->queryFind(
            $apiRequestTransfer->getFilter()->getFields()
        );

        $query = $this->apiQueryContainer->createQuery(
            $query,
            $criteriaTransfer
        );

        $query = $this->apiQueryContainer->mapPagination(
            $query,
            $apiRequestTransfer->getFilter()->getPagination()
        );

        $collection = $this->transferMapper->convertCustomerCollection($query->find());

        return new ApiCollection($collection);
    }

}
