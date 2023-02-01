<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeCriteriaTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributePersistenceFactory getFactory()
 */
class ProductAttributeRepository extends AbstractRepository implements ProductAttributeRepositoryInterface
{
    /**
     * @param array $attributes
     *
     * @return array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer>
     */
    public function findSuperAttributesFromAttributesList(array $attributes): array
    {
        $superAttributes = [];

        $mapper = $this->getFactory()
            ->createProductAttributeMapper();

        $query = $this->getFactory()
            ->createProductManagementAttributeQuery()
            ->leftJoinWithSpyProductManagementAttributeValue()
            ->innerJoinSpyProductAttributeKey()
            ->useSpyProductAttributeKeyQuery()
                ->filterByKey($attributes, Criteria::IN)
                ->filterByIsSuper(true)
            ->enduse();

        $productManagementAttributeKeyEntityCollection = $query->find();

        foreach ($productManagementAttributeKeyEntityCollection as $productManagementAttributeEntity) {
            $productManagementAttributeTransfer = $mapper->mapProductManagementAttributeEntityToTransfer($productManagementAttributeEntity, new ProductManagementAttributeTransfer());
            $superAttributes[$productManagementAttributeTransfer->getKey()] = $productManagementAttributeTransfer;
        }

        return $superAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer
     */
    public function getProductManagementAttributes(
        ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
    ): ProductManagementAttributeCollectionTransfer {
        $productManagementAttributeQuery = $this->getFactory()
            ->createProductManagementAttributeQuery()
            ->innerJoinWithSpyProductAttributeKey();

        $paginationTransfer = (new PaginationTransfer())->setNbResults($productManagementAttributeQuery->count());

        $productManagementAttributeQuery = $this->applyFilters(
            $productManagementAttributeQuery,
            $productManagementAttributeFilterTransfer,
        );

        return $this->getFactory()
            ->createProductManagementAttributeMapper()
            ->mapProductManagementAttributeEntityCollectionToTransferCollection(
                $productManagementAttributeQuery->find(),
                new ProductManagementAttributeCollectionTransfer(),
            )
            ->setPagination($paginationTransfer);
    }

    /**
     * @param array<int> $productManagementAttributeIds
     *
     * @return array<\Generated\Shared\Transfer\ProductManagementAttributeValueTransfer>
     */
    public function getProductManagementAttributeValues(array $productManagementAttributeIds): array
    {
        if (!$productManagementAttributeIds) {
            return [];
        }

        $productManagementAttributeValueQuery = $this->getFactory()
            ->createProductManagementAttributeValueQuery()
            ->filterByFkProductManagementAttribute_In($productManagementAttributeIds)
            ->leftJoinWithSpyProductManagementAttributeValueTranslation()
            ->useSpyProductManagementAttributeValueTranslationQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithSpyLocale()
            ->endUse();

        return $this->getFactory()
            ->createProductManagementAttributeMapper()
            ->mapProductManagementAttributeValueEntityCollectionToTransferCollection($productManagementAttributeValueQuery->find());
    }

    /**
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery $productManagementAttributeQuery
     * @param \Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery
     */
    protected function applyFilters(
        SpyProductManagementAttributeQuery $productManagementAttributeQuery,
        ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
    ): SpyProductManagementAttributeQuery {
        if ($productManagementAttributeFilterTransfer->getKeys()) {
            $productManagementAttributeQuery
                ->useSpyProductAttributeKeyQuery()
                ->filterByKey_In($productManagementAttributeFilterTransfer->getKeys())
                ->endUse();
        }

        if ($productManagementAttributeFilterTransfer->getOnlySuperAttributes()) {
            $productManagementAttributeQuery
                ->useSpyProductAttributeKeyQuery()
                    ->filterByIsSuper(true)
                ->endUse();
        }

        /** @var \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery $productManagementAttributeQuery */
        $productManagementAttributeQuery = $this->buildQueryFromCriteria(
            $productManagementAttributeQuery,
            $productManagementAttributeFilterTransfer->getFilter(),
        );

        $productManagementAttributeQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);

        return $productManagementAttributeQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeCriteriaTransfer $productManagementAttributeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer
     */
    public function getProductManagementAttributeCollection(
        ProductManagementAttributeCriteriaTransfer $productManagementAttributeCriteriaTransfer
    ): ProductManagementAttributeCollectionTransfer {
        $productManagementAttributeCollectionTransfer = new ProductManagementAttributeCollectionTransfer();
        $productManagementAttributeQuery = $this->getFactory()->createProductManagementAttributeQuery();
        $productManagementAttributeQuery->joinWithSpyProductAttributeKey();

        $productManagementAttributeQuery = $this->applyProductManagementAttributeConditions(
            $productManagementAttributeCriteriaTransfer,
            $productManagementAttributeQuery,
        );

        if ($productManagementAttributeCriteriaTransfer->getPagination()) {
            $productManagementAttributeQuery = $this->applyProductManagementAttributePagination(
                $productManagementAttributeCriteriaTransfer->getPagination(),
                $productManagementAttributeQuery,
            );
            $productManagementAttributeCollectionTransfer->setPagination($productManagementAttributeCriteriaTransfer->getPagination());
        }

        $productManagementAttributeEntities = $productManagementAttributeQuery->find();

        return $this->getFactory()
            ->createProductManagementAttributeMapper()
            ->mapProductManagementAttributeEntityCollectionToTransferCollection($productManagementAttributeEntities, $productManagementAttributeCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeCriteriaTransfer $productManagementAttributeCriteriaTransfer
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery $productManagementAttributeQuery
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery
     */
    protected function applyProductManagementAttributeConditions(
        ProductManagementAttributeCriteriaTransfer $productManagementAttributeCriteriaTransfer,
        SpyProductManagementAttributeQuery $productManagementAttributeQuery
    ): SpyProductManagementAttributeQuery {
        $productManagementAttributeConditionsTransfer = $productManagementAttributeCriteriaTransfer->getProductManagementAttributeConditions();
        if ($productManagementAttributeConditionsTransfer === null) {
            return $productManagementAttributeQuery;
        }

        if ($productManagementAttributeConditionsTransfer->getKeys()) {
            $productManagementAttributeQuery
                ->useSpyProductAttributeKeyQuery()
                    ->filterByKey_In($productManagementAttributeConditionsTransfer->getKeys())
                ->endUse();
        }

        return $productManagementAttributeQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery $productManagementAttributeQuery
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery
     */
    protected function applyProductManagementAttributePagination(
        PaginationTransfer $paginationTransfer,
        SpyProductManagementAttributeQuery $productManagementAttributeQuery
    ): SpyProductManagementAttributeQuery {
        if ($paginationTransfer->getPage() && $paginationTransfer->getMaxPerPage()) {
            $paginationModel = $productManagementAttributeQuery->paginate(
                $paginationTransfer->getPageOrFail(),
                $paginationTransfer->getMaxPerPageOrFail(),
            );
            $paginationTransfer
                ->setNbResults($paginationModel->getNbResults())
                ->setFirstIndex($paginationModel->getFirstIndex())
                ->setLastIndex($paginationModel->getLastIndex())
                ->setFirstPage($paginationModel->getFirstPage())
                ->setLastPage($paginationModel->getLastPage())
                ->setNextPage($paginationModel->getNextPage())
                ->setPreviousPage($paginationModel->getPreviousPage());

            /** @var \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery $productManagementAttributeQuery */
            $productManagementAttributeQuery = $paginationModel->getQuery();

            return $productManagementAttributeQuery;
        }

        $paginationTransfer->setNbResults($productManagementAttributeQuery->count());

        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            return $productManagementAttributeQuery
                ->limit($paginationTransfer->getLimitOrFail())
                ->offset($paginationTransfer->getOffsetOrFail());
        }

        return $productManagementAttributeQuery;
    }
}
