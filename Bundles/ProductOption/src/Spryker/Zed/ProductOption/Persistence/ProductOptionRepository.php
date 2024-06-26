<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence;

use Generated\Shared\Transfer\ProductAbstractOptionGroupStatusTransfer;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionGroupTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionPersistenceFactory getFactory()
 */
class ProductOptionRepository extends AbstractRepository implements ProductOptionRepositoryInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractOptionGroupStatusTransfer>
     */
    public function getProductAbstractOptionGroupStatusesByProductAbstractIds(array $productAbstractIds): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productAbstractOptionGroupStatuses */
        $productAbstractOptionGroupStatuses = $this->getFactory()
            ->createProductAbstractProductOptionGroupQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinSpyProductOptionGroup()
            ->select([
                ProductAbstractOptionGroupStatusTransfer::ID_PRODUCT_ABSTRACT,
                ProductAbstractOptionGroupStatusTransfer::IS_ACTIVE,
                ProductAbstractOptionGroupStatusTransfer::PRODUCT_OPTION_GROUP_NAME,
            ])
            ->withColumn(SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_ABSTRACT, ProductAbstractOptionGroupStatusTransfer::ID_PRODUCT_ABSTRACT)
            ->withColumn(SpyProductOptionGroupTableMap::COL_ACTIVE, ProductAbstractOptionGroupStatusTransfer::IS_ACTIVE)
            ->withColumn(SpyProductOptionGroupTableMap::COL_NAME, ProductAbstractOptionGroupStatusTransfer::PRODUCT_OPTION_GROUP_NAME)
            ->find();

        return $this->getFactory()
            ->createProductOptionMapper()
            ->mapProductAbstractOptionGroupStatusesToTransfers($productAbstractOptionGroupStatuses->toArray());
    }

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function getOrderItemsWithProductOptions(array $salesOrderItemIds): array
    {
        if (!$salesOrderItemIds) {
            return [];
        }

        $salesOrderItemOptionQuery = $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->filterByIdSalesOrderItem_In($salesOrderItemIds)
            ->leftJoinWithOption();

        return $this->getFactory()
            ->createProductOptionMapper()
            ->mapSalesOrderItemEntityCollectionToItemTransfers($salesOrderItemOptionQuery->find());
    }

    /**
     * @param array<string> $productOptionSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductOptionValueTransfer>
     */
    public function getProductOptionValuesBySkus(array $productOptionSkus): array
    {
        if (!$productOptionSkus) {
            return [];
        }

        $productOptionValueQuery = $this->getFactory()
            ->createProductOptionValueQuery()
            ->filterBySku_In($productOptionSkus);

        return $this->getFactory()
            ->createProductOptionMapper()
            ->mapProductOptionValueEntityCollectionToProductOptionValueTransfers($productOptionValueQuery->find());
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function expandProductOptionGroupQuery(ModelCriteria $query): ModelCriteria
    {
        return $this->getFactory()->createProductOptionGroupQueryExpander()->expandQuery($query);
    }
}
