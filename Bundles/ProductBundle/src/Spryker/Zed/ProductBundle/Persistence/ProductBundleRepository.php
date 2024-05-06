<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundlePersistenceFactory getFactory()
 */
class ProductBundleRepository extends AbstractRepository implements ProductBundleRepositoryInterface
{
    /**
     * @var string
     */
    protected const ALIAS_BUNDLED_PRODUCT = 'aliasBundledProduct';

    /**
     * @param string $sku
     *
     * @return array<\Generated\Shared\Transfer\ProductForBundleTransfer>
     */
    public function findBundledProductsBySku(string $sku): array
    {
        $productBundleEntities = $this->getFactory()
            ->createProductBundleQuery()
            ->joinWithSpyProductRelatedByFkProduct()
            ->useSpyProductRelatedByFkProductQuery()
                ->filterBySku($sku)
            ->endUse()
            ->find();

        return $this->getFactory()
            ->createProductBundleMapper()
            ->mapProductBundleEntitiesToProductForBundleTransfers($productBundleEntities->getArrayCopy());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleCollectionTransfer
     */
    public function getProductBundleCollectionByCriteriaFilter(
        ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer
    ): ProductBundleCollectionTransfer {
        $productBundleQuery = $this->getFactory()
            ->createProductBundleQuery()
            ->joinWithSpyProductRelatedByFkProduct();

        $this->applyFilters($productBundleCriteriaFilterTransfer, $productBundleQuery);

        $productBundleEntities = $productBundleQuery->find();

        // For BC reasons only.
        if (!$productBundleCriteriaFilterTransfer->getApplyGrouped()) {
            return $this->getFactory()
                ->createProductBundleMapper()
                ->mapProductBundleEntitiesToProductBundleCollectionTransfer($productBundleEntities->getArrayCopy(), new ProductBundleCollectionTransfer());
        }

        return $this->getFactory()
            ->createProductBundleMapper()
            ->mapProductBundleEntityCollectionToProductBundleCollectionTransfer($productBundleEntities, new ProductBundleCollectionTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery $productBundleQuery
     *
     * @return void
     */
    protected function applyFilters(
        ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer,
        SpyProductBundleQuery $productBundleQuery
    ): void {
        $bundledProductIds = array_filter(
            array_merge(
                $productBundleCriteriaFilterTransfer->getBundledProductIds(),
                [$productBundleCriteriaFilterTransfer->getIdBundledProduct()],
            ),
        );
        if ($bundledProductIds) {
            $productBundleQuery->filterByFkBundledProduct_In($bundledProductIds);
        }

        if ($productBundleCriteriaFilterTransfer->getProductConcreteIds()) {
            $productBundleQuery->filterByFkProduct_In($productBundleCriteriaFilterTransfer->getProductConcreteIds());
        }

        if ($productBundleCriteriaFilterTransfer->getIsProductConcreteActive() !== null) {
            $productBundleQuery
                ->useSpyProductRelatedByFkProductQuery()
                    ->filterByIsActive($productBundleCriteriaFilterTransfer->getIsProductConcreteActive())
                ->endUse();
        }

        if ($productBundleCriteriaFilterTransfer->getIsBundledProductActive() !== null) {
            $productBundleQuery
                ->useSpyProductRelatedByFkBundledProductQuery(static::ALIAS_BUNDLED_PRODUCT)
                    ->filterByIsActive($productBundleCriteriaFilterTransfer->getIsBundledProductActive())
                ->endUse();
        }

        if ($productBundleCriteriaFilterTransfer->getFilter()) {
            $productBundleQuery = $this->buildQueryFromCriteria(
                $productBundleQuery,
                $productBundleCriteriaFilterTransfer->getFilter(),
            );

            $productBundleQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);
        }
    }

    /**
     * @param array<string> $skus
     *
     * @return array<\Generated\Shared\Transfer\ProductForBundleTransfer>
     */
    public function getProductForBundleTransfersByProductConcreteSkus(array $skus): array
    {
        $productBundleEntities = $this->getFactory()
            ->createProductBundleQuery()
            ->joinWithSpyProductRelatedByFkProduct()
            ->useSpyProductRelatedByFkProductQuery()
                ->filterBySku_In($skus)
            ->endUse()
            ->find();

        return $this->getFactory()
            ->createProductBundleMapper()
            ->mapProductBundleEntitiesToProductForBundleTransfers($productBundleEntities->getArrayCopy());
    }

    /**
     * @module Sales
     *
     * @param array<int> $salesOrderItemIds
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function getBundleItemsBySalesOrderItemIds(array $salesOrderItemIds): array
    {
        $salesOrderItemBundleQuery = $this->getFactory()
            ->createSalesOrderItemBundlePropelQuery()
            ->joinWithSalesOrderItem()
            ->useSalesOrderItemQuery()
                ->filterByIdSalesOrderItem_In($salesOrderItemIds)
            ->endUse();

        return $this->getFactory()
            ->createProductBundleMapper()
            ->mapSalesOrderItemBundleEntitiesToItemTransfers($salesOrderItemBundleQuery->find());
    }

    /**
     * @module Product
     *
     * @param array<string> $productConcreteSkus
     *
     * @return array
     */
    public function getProductConcretesRawDataByProductConcreteSkus(array $productConcreteSkus): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productConcretesRawDataByProductConcreteSkus */
        $productConcretesRawDataByProductConcreteSkus = $this->getFactory()
            ->createProductBundleQuery()
            ->useSpyProductRelatedByFkProductQuery()
                ->filterBySku_In($productConcreteSkus)
            ->endUse()
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, ItemTransfer::ID)
            ->withColumn(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, ItemTransfer::ID_PRODUCT_ABSTRACT)
            ->withColumn(SpyProductTableMap::COL_SKU, ItemTransfer::SKU)
            ->select([
                ItemTransfer::ID,
                ItemTransfer::ID_PRODUCT_ABSTRACT,
                ItemTransfer::SKU,
            ])
            ->find();

        return $productConcretesRawDataByProductConcreteSkus->toArray(ItemTransfer::SKU);
    }

    /**
     * Result format:
     * [
     *     $idProductConcrete => ProductBundleTransfer,
     *     ...,
     * ]
     *
     * @param array<int> $productConcreteIds
     *
     * @return array<\Generated\Shared\Transfer\ProductBundleTransfer>
     */
    public function getProductBundleTransfersIndexedByIdProductConcrete(array $productConcreteIds): array
    {
        $productBundlesCollection = $this->getFactory()
            ->createProductBundleQuery()
            ->filterByFkProduct_In($productConcreteIds)
            ->joinSpyProductRelatedByFkProduct()
            ->find();

        $productBundleEntitiesIndexedByIdProduct = [];

        foreach ($productBundlesCollection as $productBundleEntity) {
            $productBundleEntitiesIndexedByIdProduct[$productBundleEntity->getFkProduct()][] = $productBundleEntity;
        }

        $productBundleMapper = $this->getFactory()->createProductBundleMapper();

        $result = [];
        foreach ($productBundleEntitiesIndexedByIdProduct as $idProductConcrete => $productBundleEntities) {
            $productForBundleTransfers = $productBundleMapper->mapProductBundleEntitiesToProductForBundleTransfers($productBundleEntities);
            $result[$idProductConcrete] = (new ProductBundleTransfer())
                ->setBundledProducts(new ArrayObject($productForBundleTransfers));
        }

        return $result;
    }
}
