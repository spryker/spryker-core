<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Model;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Spryker\Zed\Api\Business\Exception\EntityNotFoundException;
use Spryker\Zed\Api\Business\Model\ApiCollection;
use Spryker\Zed\ProductApi\Business\Mapper\TransferMapperInterface;
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
     * @var \Spryker\Zed\ProductApi\Business\Mapper\TransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @param \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface $apiQueryContainer
     * @param \Spryker\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductApi\Business\Mapper\TransferMapperInterface $transferMapper
     */
    public function __construct(
        ProductApiToApiInterface $apiQueryContainer,
        ProductApiQueryContainerInterface $queryContainer,
        TransferMapperInterface $transferMapper
    ) {
        $this->apiQueryContainer = $apiQueryContainer;
        $this->queryContainer = $queryContainer;
        $this->transferMapper = $transferMapper;
    }

    /**
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function get($idProduct, ApiFilterTransfer $apiFilterTransfer)
    {
        $productData = (array)$this->queryContainer
            ->queryProductAbstractById($idProduct, $apiFilterTransfer->getFields())
            ->findOne();

        if (!$productData) {
            throw new EntityNotFoundException('Product not found idProduct: ' . $idProduct);
        }

        return $this->transferMapper->toTransfer($productData);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer|\Generated\Shared\Transfer\ProductApiTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function add(ApiDataTransfer $apiDataTransfer)
    {
        return $this->persist($apiDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function update(ApiDataTransfer $apiDataTransfer)
    {
        return $this->persist($apiDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    protected function persist(ApiDataTransfer $apiDataTransfer)
    {
        $productData = new SpyCustomer();
        $productData->fromArray($apiDataTransfer->toArray());

        $productData->save();

        return $this->transferMapper->toTransfer($productData->toArray());
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
     * @return \Spryker\Zed\Api\Business\Model\ApiCollectionInterface //TODO should return transfer, replace ApiRequest with ApiFilter
     */
    public function find(ApiRequestTransfer $apiRequestTransfer)
    {
        $criteriaTransfer = $this->buildPropelQueryBuilderCriteria($apiRequestTransfer);
        $query = $this->buildQuery($apiRequestTransfer, $criteriaTransfer);

        $collection = $this->transferMapper->toTransferCollection($query->find());

        return new ApiCollection($collection); //TODO map to transfer
    }

    /**
     * TODO invert this, and put it into Api bundle
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function buildPropelQueryBuilderCriteria(ApiRequestTransfer $apiRequestTransfer)
    {
        $criteriaRuleSet = $this->apiQueryContainer
            ->createPropelQueryBuilderCriteriaFromJson($apiRequestTransfer->getFilter()
            ->getFilter());

        $criteriaTransfer = new PropelQueryBuilderCriteriaTransfer();
        $criteriaTransfer->setRuleSet($criteriaRuleSet);

        return $criteriaTransfer;
    }

    /**
     * TODO invert this, and put it into Api bundle
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $criteriaTransfer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function buildQuery(ApiRequestTransfer $apiRequestTransfer, PropelQueryBuilderCriteriaTransfer $criteriaTransfer)
    {
        $query = $this->queryContainer->queryFind($apiRequestTransfer->getFilter()->getFields());
        $query = $this->apiQueryContainer->createQuery($query, $criteriaTransfer);
        $query = $this->apiQueryContainer->mapPagination($query, $apiRequestTransfer->getFilter()->getPagination());

        return $query;
    }

}
