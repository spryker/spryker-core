<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence;

use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundlePersistenceFactory getFactory()
 */
class ProductBundleRepository extends AbstractRepository implements ProductBundleRepositoryInterface
{
    protected const COL_ID_PRODUCT = 'id_product';
    protected const COL_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';
    protected const COL_SKU = 'sku';

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer[]
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

        if ($productBundleCriteriaFilterTransfer->getIdBundledProduct()) {
            $productBundleQuery->filterByFkBundledProduct($productBundleCriteriaFilterTransfer->getIdBundledProduct());
        }

        $productBundleEntities = $productBundleQuery->find();

        return $this->getFactory()
            ->createProductBundleMapper()
            ->mapProductBundleEntitiesToProductBundleCollectionTransfer($productBundleEntities->getArrayCopy(), new ProductBundleCollectionTransfer());
    }

    /**
     * @param string[] $skus
     *
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer[]
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
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getBundleItemsBySalesOrderItemIds(array $salesOrderItemIds): array
    {
        $salesOrderItemBundleEntities = $this->getFactory()
            ->createSalesOrderItemBundleQuery()
            ->joinWithSalesOrderItem()
            ->useSalesOrderItemQuery()
                ->filterByIdSalesOrderItem_In($salesOrderItemIds)
            ->endUse()
            ->find();

        return $this->getFactory()
            ->createProductBundleMapper()
            ->mapSalesOrderItemEntitiesToBundleItemTransfers($salesOrderItemBundleEntities);
    }

    /**
     * @module Product
     *
     * @param string[] $productConcreteSkus
     *
     * @return array
     */
    public function getProductConcretesRawDataByProductConcreteSkus(array $productConcreteSkus): array
    {
        return $this->getFactory()
            ->createProductBundleQuery()
            ->clearSelectColumns()
            ->addAsColumn(static::COL_ID_PRODUCT, SpyProductTableMap::COL_ID_PRODUCT)
            ->addAsColumn(static::COL_FK_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->addAsColumn(static::COL_SKU, SpyProductTableMap::COL_SKU)
            ->useSpyProductRelatedByFkProductQuery()
                ->filterBySku_In($productConcreteSkus)
            ->endUse()
            ->find()
            ->toArray(static::COL_SKU);
    }
}
