<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Model;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\Api\Business\Exception\EntityNotFoundException;
use Spryker\Zed\ProductApi\Business\Mapper\EntityMapperInterface;
use Spryker\Zed\ProductApi\Business\Mapper\TransferMapperInterface;
use Spryker\Zed\ProductApi\Dependency\Facade\ProductApiToProductInterface;
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
     * @var \Spryker\Zed\ProductApi\Dependency\Facade\ProductApiToProductInterface
     */
    private $productFacade;

    /**
     * @param \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface $apiQueryContainer
     * @param \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiQueryBuilderInterface $apiQueryBuilderQueryContainer
     * @param \Spryker\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductApi\Business\Mapper\EntityMapperInterface $entityMapper
     * @param \Spryker\Zed\ProductApi\Business\Mapper\TransferMapperInterface $transferMapper
     * @param \Spryker\Zed\ProductApi\Dependency\Facade\ProductApiToProductInterface $productFacade
     */
    public function __construct(
        ProductApiToApiInterface $apiQueryContainer,
        ProductApiToApiQueryBuilderInterface $apiQueryBuilderQueryContainer,
        ProductApiQueryContainerInterface $queryContainer,
        EntityMapperInterface $entityMapper,
        TransferMapperInterface $transferMapper,
        ProductApiToProductInterface $productFacade
    ) {
        $this->apiQueryContainer = $apiQueryContainer;
        $this->apiQueryBuilderQueryContainer = $apiQueryBuilderQueryContainer;
        $this->queryContainer = $queryContainer;
        $this->entityMapper = $entityMapper;
        $this->transferMapper = $transferMapper;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function add(ApiDataTransfer $apiDataTransfer)
    {
        $data = (array)$apiDataTransfer->getData();

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->fromArray($data, true);

        $productConcreteCollection = [];
        if (!isset($data['product_concretes'])) {
            $data['product_concretes'] = [];
        }
        foreach ($data['product_concretes'] as $productConcrete) {
            $productConcreteCollection[] = (new ProductConcreteTransfer())->fromArray($productConcrete, true);
        }

        $idProductAbstract = $this->productFacade->addProduct($productAbstractTransfer, $productConcreteCollection);

        return $this->get($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function get($idProductAbstract)
    {
        $productTransfer = $this->productFacade->findProductAbstractById($idProductAbstract);
        if (!$productTransfer) {
            throw new EntityNotFoundException(sprintf('Product Abstract not found for id %s', $idProductAbstract));
        }

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
        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $productAbstractTransfer->fromArray($data, true);

        $productConcreteCollection = [];
        if (!isset($data['product_concretes'])) {
            $data['product_concretes'] = [];
        }
        foreach ($data['product_concretes'] as $productConcrete) {
            $productConcreteCollection[] = (new ProductConcreteTransfer())->fromArray($productConcrete, true);
        }

        $idProductAbstract = $this->productFacade->saveProduct($productAbstractTransfer, $productConcreteCollection);

        return $this->get($idProductAbstract);
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

        foreach ($collection as $k => $productAbstractTransfer) {
            $collection[$k] = $this->get($productAbstractTransfer->getIdProductAbstract())->getData();
        }

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

}
