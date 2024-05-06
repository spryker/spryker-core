<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeCriteriaTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchTableMap;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;

/**
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchPersistenceFactory getFactory()
 */
class ProductSearchRepository extends AbstractRepository implements ProductSearchRepositoryInterface
{
    /**
     * @var string
     */
    protected const COL_COUNT = 'count';

    /**
     * Result format:
     * [
     *     $idProduct => [$idLocale => $count],
     *     ...,
     * ]
     *
     * @param array<int> $productIds
     * @param array<int> $localeIds
     *
     * @return array<int, array<int, int>>
     */
    public function getProductSearchEntitiesCountGroupedByIdProductAndIdLocale(array $productIds, array $localeIds): array
    {
        $productSearchQuery = $this->getFactory()->createProductSearchQuery()
            ->select('COUNT(*)')
            ->filterByFkProduct_In($productIds)
            ->filterByIsSearchable(true)
            ->filterByFkLocale_In($localeIds)
            ->groupByFkProduct()
            ->groupByFkLocale()
            ->select([SpyProductSearchTableMap::COL_FK_PRODUCT, SpyProductSearchTableMap::COL_FK_LOCALE])
            ->withColumn('COUNT(*)', static::COL_COUNT);

        $result = [];

        foreach ($productSearchQuery->find() as $productSearchData) {
            $idProduct = (int)$productSearchData[SpyProductSearchTableMap::COL_FK_PRODUCT];
            $idLocale = (int)$productSearchData[SpyProductSearchTableMap::COL_FK_LOCALE];
            $result[$idProduct][$idLocale] = $productSearchData[static::COL_COUNT];
        }

        return $result;
    }

    /**
     * @return array<string>
     */
    public function getAllProductAttributeKeys(): array
    {
        /** @var array<string> $allProductAttributeKeys */
        $allProductAttributeKeys = $this->getFactory()
            ->createProductAttributeKeyQuery()
            ->addSelectColumn(SpyProductAttributeKeyTableMap::COL_KEY)
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        return $allProductAttributeKeys;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeCriteriaTransfer $productSearchAttributeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer
     */
    public function getProductSearchAttributeCollection(
        ProductSearchAttributeCriteriaTransfer $productSearchAttributeCriteriaTransfer
    ): ProductSearchAttributeCollectionTransfer {
        $productSearchAttributeQuery = $this->getFactory()
            ->createProductSearchAttributeQuery()
            ->joinWithSpyProductAttributeKey();

        $productSearchAttributeQuery = $this->applyProductSearchAttributeFilters($productSearchAttributeQuery, $productSearchAttributeCriteriaTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers */
        $sortTransfers = $productSearchAttributeCriteriaTransfer->getSortCollection();
        $productSearchAttributeQuery = $this->applySorting($productSearchAttributeQuery, $sortTransfers);

        return $this->getFactory()
            ->createProductSearchAttributeMapper()
            ->mapProductSearchAttributeEntitiesToProductSearchAttributeCollectionTransfer(
                $productSearchAttributeQuery->find(),
                new ProductSearchAttributeCollectionTransfer(),
            );
    }

    /**
     * @param \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery $productSearchAttributeQuery
     * @param \Generated\Shared\Transfer\ProductSearchAttributeCriteriaTransfer $productSearchAttributeCriteriaTransfer
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery
     */
    protected function applyProductSearchAttributeFilters(
        SpyProductSearchAttributeQuery $productSearchAttributeQuery,
        ProductSearchAttributeCriteriaTransfer $productSearchAttributeCriteriaTransfer
    ): SpyProductSearchAttributeQuery {
        $productSearchAttributeConditionsTransfer = $productSearchAttributeCriteriaTransfer->getProductSearchAttributeConditions();

        if (!$productSearchAttributeConditionsTransfer) {
            return $productSearchAttributeQuery;
        }

        if ($productSearchAttributeConditionsTransfer->getProductSearchAttributeIds()) {
            $productSearchAttributeQuery->filterByIdProductSearchAttribute_In($productSearchAttributeConditionsTransfer->getProductSearchAttributeIds());
        }

        return $productSearchAttributeQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applySorting(
        ModelCriteria $modelCriteria,
        ArrayObject $sortTransfers
    ): ModelCriteria {
        foreach ($sortTransfers as $sortTransfer) {
            $modelCriteria->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $modelCriteria;
    }
}
