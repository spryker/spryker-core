<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Model;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ProductApiTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Spryker\Zed\Api\Business\Exception\EntityNotFoundException;
use Spryker\Zed\Api\Business\Model\ApiCollection;
use Spryker\Zed\ProductApi\Business\Transfer\CustomerTransferMapperInterface;
use Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface;
use Spryker\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface;

class ProductApi
{

    /**
     * @var \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface
     */
    protected $apiQueryContainer;

    /**
     * @var \Spryker\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductApi\Business\Transfer\CustomerTransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @param \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface $apiQueryContainer
     * @param \Spryker\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductApi\Business\Transfer\CustomerTransferMapperInterface $transferMapper
     */
    public function __construct(
        ProductApiToApiInterface $apiQueryContainer,
        ProductApiQueryContainerInterface $queryContainer,
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
     * @return \Generated\Shared\Transfer\ProductApiTransfer
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
     * @param \Generated\Shared\Transfer\ProductApiTransfer $productApiTransfer
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function add(ApiDataTransfer $productApiTransfer)
    {
        return $this->persist($productApiTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function update(ApiDataTransfer $productApiTransfer)
    {
        return $this->persist($productApiTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $productApiTransfer
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    protected function persist(ApiDataTransfer $productApiTransfer)
    {
        $customerData = new SpyCustomer();
        $customerData->fromArray($productApiTransfer->toArray());

        $customerData->save();

        $productApiTransfer = new ProductApiTransfer();
        $productApiTransfer->fromArray($customerData->toArray(), true);

        return $productApiTransfer;
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
