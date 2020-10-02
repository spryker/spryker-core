<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
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
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
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
            $productManagementAttributeFilterTransfer
        );

        return $this->getFactory()
            ->createProductManagementAttributeMapper()
            ->mapProductManagementAttributeEntityCollectionToTransferCollection(
                $productManagementAttributeQuery->find(),
                new ProductManagementAttributeCollectionTransfer()
            )
            ->setPagination($paginationTransfer);
    }

    /**
     * @param int[] $productManagementAttributeIds
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]
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

        /** @var \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery $productManagementAttributeQuery */
        $productManagementAttributeQuery = $this->buildQueryFromCriteria(
            $productManagementAttributeQuery,
            $productManagementAttributeFilterTransfer->getFilter()
        );

        $productManagementAttributeQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);

        return $productManagementAttributeQuery;
    }
}
