<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Model;

use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiPaginationTransfer;
use Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiValidationErrorTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\ProductApi\Business\Mapper\EntityMapperInterface;
use Spryker\Zed\ProductApi\Business\Mapper\TransferMapperInterface;
use Spryker\Zed\ProductApi\Business\Request\ProductRequestDataInterface;
use Spryker\Zed\ProductApi\Dependency\Facade\ProductApiToApiFacadeInterface;
use Spryker\Zed\ProductApi\Dependency\Facade\ProductApiToProductInterface;
use Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiQueryBuilderInterface;
use Spryker\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductApi implements ProductApiInterface
{
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
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductApi\Dependency\Facade\ProductApiToApiFacadeInterface
     */
    protected $apiFacade;

    /**
     * @param \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiQueryBuilderInterface $apiQueryBuilderQueryContainer
     * @param \Spryker\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductApi\Business\Mapper\EntityMapperInterface $entityMapper
     * @param \Spryker\Zed\ProductApi\Business\Mapper\TransferMapperInterface $transferMapper
     * @param \Spryker\Zed\ProductApi\Dependency\Facade\ProductApiToProductInterface $productFacade
     * @param \Spryker\Zed\ProductApi\Dependency\Facade\ProductApiToApiFacadeInterface $apiFacade
     */
    public function __construct(
        ProductApiToApiQueryBuilderInterface $apiQueryBuilderQueryContainer,
        ProductApiQueryContainerInterface $queryContainer,
        EntityMapperInterface $entityMapper,
        TransferMapperInterface $transferMapper,
        ProductApiToProductInterface $productFacade,
        ProductApiToApiFacadeInterface $apiFacade
    ) {
        $this->apiQueryBuilderQueryContainer = $apiQueryBuilderQueryContainer;
        $this->queryContainer = $queryContainer;
        $this->entityMapper = $entityMapper;
        $this->transferMapper = $transferMapper;
        $this->productFacade = $productFacade;
        $this->apiFacade = $apiFacade;
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
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function get($idProductAbstract)
    {
        $productTransfer = $this->productFacade->findProductAbstractById($idProductAbstract);
        if (!$productTransfer) {
            return $this->createAbstractProductNotFoundApiItemTransfer($idProductAbstract);
        }

        return $this->apiFacade->createApiItem($productTransfer, (string)$idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
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
            return $this->createAbstractProductNotFoundApiItemTransfer($idProductAbstract);
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
            $query->find()->toArray(),
        );

        $apiCollectionTransfer = $this->apiFacade->createApiCollection($collection);

        $apiCollectionTransfer = $this->addPagination($query, $apiCollectionTransfer, $apiRequestTransfer);

        return $apiCollectionTransfer;
    }

    /**
     * //FIXME: Move to generic place
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiCollectionTransfer $apiCollectionTransfer
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    protected function addPagination(ModelCriteria $query, ApiCollectionTransfer $apiCollectionTransfer, ApiRequestTransfer $apiRequestTransfer)
    {
        $query->setOffset(0);
        $query->setLimit(-1);
        $total = $query->count();

        $apiFilterTransfer = $apiRequestTransfer->getFilter();
        if ($apiFilterTransfer !== null) {
            $page = $apiFilterTransfer->getLimit() ? ($apiFilterTransfer->getOffset() / $apiFilterTransfer->getLimit() + 1) : 1;
            $pageTotal = ($total && $apiFilterTransfer->getLimit()) ? (int)ceil($total / $apiFilterTransfer->getLimit()) : 1;

            if ($page > $pageTotal) {
                throw new NotFoundHttpException('Out of bounds.', null, ApiConfig::HTTP_CODE_NOT_FOUND);
            }

            $apiPaginationTransfer = new ApiPaginationTransfer();
            $apiPaginationTransfer->setItemsPerPage($apiFilterTransfer->getLimit());
            $apiPaginationTransfer->setPage($page);
            $apiPaginationTransfer->setTotal($total);
            $apiPaginationTransfer->setPageTotal($pageTotal);

            $apiCollectionTransfer->setPagination($apiPaginationTransfer);
        }

        return $apiCollectionTransfer;
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
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    protected function createAbstractProductNotFoundApiItemTransfer(int $idProductAbstract): ApiItemTransfer
    {
        $apiValidationErrorTransfer = $this->createApiValidationErrorTransfer(
            sprintf('Product not found: %s', $idProductAbstract),
            ProductRequestDataInterface::KEY_ID,
        );

        return $this->apiFacade
            ->createApiItem(null, (string)$idProductAbstract)
            ->setStatusCode(ApiConfig::HTTP_CODE_NOT_FOUND)
            ->addValidationError($apiValidationErrorTransfer);
    }

    /**
     * @param string $message
     * @param string|null $field
     *
     * @return \Generated\Shared\Transfer\ApiValidationErrorTransfer
     */
    protected function createApiValidationErrorTransfer(string $message, ?string $field = null): ApiValidationErrorTransfer
    {
        return (new ApiValidationErrorTransfer())
            ->setField($field)
            ->addMessages($message);
    }
}
