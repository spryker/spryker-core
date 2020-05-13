<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence;

use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundlePersistenceFactory getFactory()
 */
class ProductBundleRepository extends AbstractRepository implements ProductBundleRepositoryInterface
{
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
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getBundleItemsBySalesOrderItemIds(array $salesOrderItemIds): array
    {
        $salesOrderItemEntities = $this->getSalesOrderItemEntitiesBySalesOrderItemIds($salesOrderItemIds);

        if (!$salesOrderItemEntities->count()) {
            return [];
        }

        $salesOrderItemBundleEntities = $this->getFactory()
            ->createSalesOrderItemBundleQuery()
            ->filterByIdSalesOrderItemBundle_In($this->getSalesOrderItemBundleIds($salesOrderItemEntities))
            ->find();

        $salesOrderItemBundleEntities = $this->indexSalesOrderItemEntitiesByFkSalesOrderItemBundle($salesOrderItemBundleEntities);

        return $this->getFactory()
            ->createProductBundleMapper()
            ->mapSalesOrderItemEntitiesToBundleItemTransfers($salesOrderItemEntities, $salesOrderItemBundleEntities);
    }

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getSalesOrderItemEntitiesBySalesOrderItemIds(array $salesOrderItemIds): ObjectCollection
    {
        return $this->getFactory()
            ->getSalesOrderItemPropelQuery()
            ->filterByIdSalesOrderItem_In($salesOrderItemIds)
            ->filterByFkSalesOrderItemBundle(null, Criteria::ISNOTNULL)
            ->find();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Propel\Runtime\Collection\ObjectCollection $salesOrderItemEntities
     *
     * @return int[]
     */
    protected function getSalesOrderItemBundleIds(ObjectCollection $salesOrderItemEntities): array
    {
        $salesOrderItemBundleIds = [];

        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $salesOrderItemBundleIds[] = $salesOrderItemEntity->getFkSalesOrderItemBundle();
        }

        return $salesOrderItemBundleIds;
    }

    /**
     * @param \Orm\Zed\ProductBundle\Persistence\SpySalesOrderItemBundle[]|\Propel\Runtime\Collection\ObjectCollection $salesOrderItemBundleEntities
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpySalesOrderItemBundle[]
     */
    protected function indexSalesOrderItemEntitiesByFkSalesOrderItemBundle(ObjectCollection $salesOrderItemBundleEntities): array
    {
        $indexedSalesOrderItemBundleEntities = [];

        foreach ($salesOrderItemBundleEntities as $salesOrderItemBundleEntity) {
            $indexedSalesOrderItemBundleEntities[$salesOrderItemBundleEntity->getIdSalesOrderItemBundle()] = $salesOrderItemBundleEntity;
        }

        return $indexedSalesOrderItemBundleEntities;
    }
}
