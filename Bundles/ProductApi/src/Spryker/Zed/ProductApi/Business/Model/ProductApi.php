<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Model;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ProductApiTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\Api\Business\Exception\EntityNotFoundException;
use Spryker\Zed\ProductApi\Business\Mapper\EntityMapperInterface;
use Spryker\Zed\ProductApi\Business\Mapper\TransferMapperInterface;
use Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface;
use Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiQueryBuilderInterface;
use Spryker\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface;

class ProductApi implements ProductApiInterface
{

    /**
     * @var \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface
     */
    protected $apiQueryContainer;

    /**
     * @var \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiQueryBuilderInterface
     */
    protected $apiQueryBuilderQueryContainer;

    /**
     * @var \Spryker\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductApi\Business\Mapper\EntityMapperInterface
     */
    protected $entityMapper;

    /**
     * @var \Spryker\Zed\ProductApi\Business\Mapper\TransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @param \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface $apiQueryContainer
     * @param \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiQueryBuilderInterface $apiQueryBuilderQueryContainer
     * @param \Spryker\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductApi\Business\Mapper\EntityMapperInterface $entityMapper
     * @param \Spryker\Zed\ProductApi\Business\Mapper\TransferMapperInterface $transferMapper
     */
    public function __construct(
        ProductApiToApiInterface $apiQueryContainer,
        ProductApiToApiQueryBuilderInterface $apiQueryBuilderQueryContainer,
        ProductApiQueryContainerInterface $queryContainer,
        EntityMapperInterface $entityMapper,
        TransferMapperInterface $transferMapper
    ) {
        $this->apiQueryContainer = $apiQueryContainer;
        $this->apiQueryBuilderQueryContainer = $apiQueryBuilderQueryContainer;
        $this->queryContainer = $queryContainer;
        $this->entityMapper = $entityMapper;
        $this->transferMapper = $transferMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function add(ApiDataTransfer $apiDataTransfer)
    {
        $productEntity = $this->entityMapper->toEntity($apiDataTransfer->getData());
        $productApiTransfer = $this->persist($productEntity);

        return $this->apiQueryContainer->createApiItem($productApiTransfer, $productEntity->getIdProductAbstract());
    }

    /**
     * @internal param ApiFilterTransfer $apiFilterTransfer
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function get($idProductAbstract)
    {
        $productData = $this->getProductData($idProductAbstract);
        $productTransfer = $this->transferMapper->toTransfer($productData);

        return $this->apiQueryContainer->createApiItem($productTransfer, $idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function update($idProductAbstract, ApiDataTransfer $apiDataTransfer)
    {
        $entityToUpdate = $this->queryContainer
            ->queryFind()
            ->filterByIdProductAbstract($idProductAbstract)
            ->findOne();

        if (!$entityToUpdate) {
            throw new EntityNotFoundException(sprintf('Product not found: %s', $idProductAbstract));
        }

        $data = (array)$apiDataTransfer->getData();
        $entityToUpdate->fromArray($data);

        $productApiTransfer = $this->persist($entityToUpdate);

        return $this->apiQueryContainer->createApiItem($productApiTransfer, $entityToUpdate->getIdProductAbstract());
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function remove($idProductAbstract)
    {
        $deletedRows = $this->queryContainer
            ->queryRemove($idProductAbstract)
            ->delete();

        $productApiTransfer = new ProductApiTransfer();

        if ($deletedRows > 0) {
            $productApiTransfer->setIdProductAbstract($idProductAbstract);
        }

        return $this->apiQueryContainer->createApiItem($productApiTransfer, $idProductAbstract);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function find(ApiRequestTransfer $apiRequestTransfer)
    {
        $query = $this->buildQuery($apiRequestTransfer);

        $collection = $this->transferMapper->toTransferCollection(
            $query->find()->toArray()
        );

        return $this->apiQueryContainer->createApiCollection($collection);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function buildQuery(ApiRequestTransfer $apiRequestTransfer)
    {
        $apiQueryBuilderQueryTransfer = $this->buildApiQueryBuilderQuery($apiRequestTransfer);

        $query = $this->queryContainer->queryFind();
        $query = $this->apiQueryBuilderQueryContainer->buildQueryFromRequest($query, $apiQueryBuilderQueryTransfer);

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer
     */
    protected function buildApiQueryBuilderQuery(ApiRequestTransfer $apiRequestTransfer)
    {
        $columnSelectionTransfer = $this->buildColumnSelection();

        $apiQueryBuilderQueryTransfer = new ApiQueryBuilderQueryTransfer();
        $apiQueryBuilderQueryTransfer->setApiRequest($apiRequestTransfer);
        $apiQueryBuilderQueryTransfer->setColumnSelection($columnSelectionTransfer);

        return $apiQueryBuilderQueryTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer
     */
    protected function buildColumnSelection()
    {
        $columnSelectionTransfer = new PropelQueryBuilderColumnSelectionTransfer();
        $tableColumns = SpyProductAbstractTableMap::getFieldNames(TableMap::TYPE_FIELDNAME);

        foreach ($tableColumns as $columnAlias) {
            $columnTransfer = new PropelQueryBuilderColumnTransfer();
            $columnTransfer->setName(SpyProductAbstractTableMap::TABLE_NAME . '.' . $columnAlias);
            $columnTransfer->setAlias($columnAlias);

            $columnSelectionTransfer->addTableColumn($columnTransfer);
        }

        return $columnSelectionTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $entity
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    protected function persist(SpyProductAbstract $entity)
    {
        $entity->save();

        return $this->transferMapper->toTransfer($entity->toArray());
    }

    /**
     * @param int $idProduct
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotFoundException
     *
     * @return array
     */
    protected function getProductData($idProduct)
    {
        //TODO column filtering
        $productArray = (array)$this->queryContainer
            ->queryGet($idProduct)
            ->findOne()
            ->toArray();

        if (!$productArray) {
            throw new EntityNotFoundException(sprintf('Product not found: %s', $idProduct));
        }

        return $productArray;
    }

}
