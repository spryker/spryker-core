<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAbstractOptionGroupStatusTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\Collection;

class ProductOptionMapper
{
    /**
     * @param array<int, array<string, int|bool|string>> $productAbstractOptionGroupStatuses
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractOptionGroupStatusTransfer>
     */
    public function mapProductAbstractOptionGroupStatusesToTransfers(
        array $productAbstractOptionGroupStatuses
    ): array {
        $productAbstractOptionGroupStatusTransfers = [];
        foreach ($productAbstractOptionGroupStatuses as $productAbstractOptionGroupStatus) {
            $productAbstractOptionGroupStatusTransfers[] = $this->mapProductAbstractOptionGroupStatusToTransfer(
                $productAbstractOptionGroupStatus,
            );
        }

        return $productAbstractOptionGroupStatusTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItemEntities
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function mapSalesOrderItemEntityCollectionToItemTransfers(
        Collection $salesOrderItemEntities
    ): array {
        $itemTransfers = [];

        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $itemTransfers[] = (new ItemTransfer())
                ->fromArray($salesOrderItemEntity->toArray(), true)
                ->setSumProductOptionPriceAggregation($salesOrderItemEntity->getProductOptionPriceAggregation())
                ->setProductOptions(
                    new ArrayObject($this->mapSalesOrderItemEntityToProductOptionTransfers($salesOrderItemEntity)),
                );
        }

        return $itemTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\ProductOption\Persistence\SpyProductOptionValue> $productOptionValueEntities
     *
     * @return array<\Generated\Shared\Transfer\ProductOptionValueTransfer>
     */
    public function mapProductOptionValueEntityCollectionToProductOptionValueTransfers(
        Collection $productOptionValueEntities
    ): array {
        $productOptionValueTransfers = [];

        foreach ($productOptionValueEntities as $productOptionValueEntity) {
            $productOptionValueTransfers[] = (new ProductOptionValueTransfer())
                ->fromArray($productOptionValueEntity->toArray(), true);
        }

        return $productOptionValueTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Sales\Persistence\SpySalesOrderItemOption> $salesOrderItemOptionEntities
     *
     * @return array<int, array<int, \Orm\Zed\Sales\Persistence\SpySalesOrderItemOption>>
     */
    public function mapSalesOrderItemOptionEntitiesToSalesOrderItemOptionEntitiesGroupedByIdSalesOrderItem(
        Collection $salesOrderItemOptionEntities
    ): array {
        $salesOrderItemOptionEntitiesGroupedByIdSalesOrderItem = [];

        foreach ($salesOrderItemOptionEntities as $salesOrderItemOptionEntity) {
            $idSalesOrderItem = $salesOrderItemOptionEntity->getFkSalesOrderItem();
            $idSalesOrderItemOption = $salesOrderItemOptionEntity->getIdSalesOrderItemOption();
            $salesOrderItemOptionEntitiesGroupedByIdSalesOrderItem[$idSalesOrderItem][$idSalesOrderItemOption]
                = $salesOrderItemOptionEntity;
        }

        return $salesOrderItemOptionEntitiesGroupedByIdSalesOrderItem;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     *
     * @return array<\Generated\Shared\Transfer\ProductOptionTransfer>
     */
    protected function mapSalesOrderItemEntityToProductOptionTransfers(SpySalesOrderItem $salesOrderItemEntity): array
    {
        $productOptionTransfers = [];

        foreach ($salesOrderItemEntity->getOptions() as $salesOrderItemOptionEntity) {
            $productOptionTransfers[] = (new ProductOptionTransfer())
                ->fromArray($salesOrderItemOptionEntity->toArray(), true)
                ->setQuantity($salesOrderItemEntity->getQuantity())
                ->setSumPrice($salesOrderItemOptionEntity->getPrice())
                ->setSumGrossPrice($salesOrderItemOptionEntity->getGrossPrice())
                ->setSumNetPrice($salesOrderItemOptionEntity->getNetPrice())
                ->setSumDiscountAmountAggregation($salesOrderItemOptionEntity->getDiscountAmountAggregation())
                ->setSumTaxAmount($salesOrderItemOptionEntity->getTaxAmount());
        }

        return $productOptionTransfers;
    }

    /**
     * @param array<string, int|bool|string> $productAbstractOptionGroupStatus
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionGroupStatusTransfer
     */
    protected function mapProductAbstractOptionGroupStatusToTransfer(
        array $productAbstractOptionGroupStatus
    ): ProductAbstractOptionGroupStatusTransfer {
        return (new ProductAbstractOptionGroupStatusTransfer())
            ->fromArray($productAbstractOptionGroupStatus);
    }
}
