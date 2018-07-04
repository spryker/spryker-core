<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;

class ProductOptionOrderHydrate implements ProductOptionOrderHydrateInterface
{
    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     */
    public function __construct(ProductOptionQueryContainerInterface $productOptionQueryContainer)
    {
        $this->productOptionQueryContainer = $productOptionQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrate(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireIdSalesOrder();

        $salesOrderItems = $this->getSalesOrderItemsByIdSalesOrder($orderTransfer->getIdSalesOrder());

        foreach ($salesOrderItems as $salesOrderItemEntity) {
            $itemTransfer = $this->findItemTransferOptionsBelongTo(
                $orderTransfer,
                $salesOrderItemEntity->getIdSalesOrderItem()
            );

            if ($itemTransfer === null) {
                continue;
            }

            $itemTransfer->setSumProductOptionPriceAggregation($salesOrderItemEntity->getProductOptionPriceAggregation());

            // BC: Unit prices are populated for BC reasons only
            $this->setItemTransferUnitPrices($itemTransfer, $salesOrderItemEntity);

            foreach ($salesOrderItemEntity->getOptions() as $orderItemOptionEntity) {
                $productOptionsTransfer = $this->hydrateProductOptionTransfer($orderItemOptionEntity, $salesOrderItemEntity->getQuantity());
                $itemTransfer->addProductOption($productOptionsTransfer);
            }
        }

        return $orderTransfer;
    }

    /**
     * @deprecated Derives unit prices using sum prices which is accurate for quantity = 1 only
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     *
     * @return void
     */
    protected function setItemTransferUnitPrices(ItemTransfer $itemTransfer, SpySalesOrderItem $salesOrderItemEntity)
    {
        $itemTransfer->setUnitProductOptionPriceAggregation($salesOrderItemEntity->getProductOptionPriceAggregation() / $salesOrderItemEntity->getQuantity());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItemTransferOptionsBelongTo(OrderTransfer $orderTransfer, $idSalesOrderItem)
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIdSalesOrderItem() === $idSalesOrderItem) {
                return $itemTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemOption $orderItemOptionEntity
     * @param int $orderItemQuantity
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function hydrateProductOptionTransfer(SpySalesOrderItemOption $orderItemOptionEntity, $orderItemQuantity)
    {
        $productOptionsTransfer = new ProductOptionTransfer();
        $productOptionsTransfer->setQuantity($orderItemQuantity);

        $productOptionsTransfer->setSumPrice($orderItemOptionEntity->getPrice());
        $productOptionsTransfer->setSumGrossPrice($orderItemOptionEntity->getGrossPrice());
        $productOptionsTransfer->setSumNetPrice($orderItemOptionEntity->getNetPrice());
        $productOptionsTransfer->setSumDiscountAmountAggregation($orderItemOptionEntity->getDiscountAmountAggregation());
        $productOptionsTransfer->setSumTaxAmount($orderItemOptionEntity->getTaxAmount());
        $productOptionsTransfer->fromArray($orderItemOptionEntity->toArray(), true);

        // BC: Unit prices are populated for BC reasons only
        $this->setProductOptionTransferUnitPrices($productOptionsTransfer);

        $idProductOptionsValue = $this->getIdProductOptionValue($orderItemOptionEntity);
        if ($idProductOptionsValue) {
            $productOptionsTransfer->setIdProductOptionValue($idProductOptionsValue);
        }

        return $productOptionsTransfer;
    }

    /**
     * @deprecated Derives unit prices using sum prices which is accurate for quantity = 1 only
     *
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return void
     */
    protected function setProductOptionTransferUnitPrices(ProductOptionTransfer $productOptionTransfer)
    {
        $productOptionTransfer->setUnitPrice((int)round($productOptionTransfer->getSumPrice() / $productOptionTransfer->getQuantity()));
        $productOptionTransfer->setUnitGrossPrice((int)round($productOptionTransfer->getSumGrossPrice() / $productOptionTransfer->getQuantity()));
        $productOptionTransfer->setUnitNetPrice((int)round($productOptionTransfer->getSumNetPrice() / $productOptionTransfer->getQuantity()));
        $productOptionTransfer->setUnitDiscountAmountAggregation((int)round($productOptionTransfer->getSumDiscountAmountAggregation() / $productOptionTransfer->getQuantity()));
        $productOptionTransfer->setUnitTaxAmount((int)round($productOptionTransfer->getSumTaxAmount() / $productOptionTransfer->getQuantity()));
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getSalesOrderItemsByIdSalesOrder($idSalesOrder)
    {
        return $this->productOptionQueryContainer
            ->querySalesOrder()
            ->filterByFkSalesOrder($idSalesOrder)
            ->find();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemOption $orderItemOptionEntity
     *
     * @return int|null
     */
    protected function getIdProductOptionValue(SpySalesOrderItemOption $orderItemOptionEntity): ?int
    {
        return $this->productOptionQueryContainer
            ->queryProductOptionValueBySku($orderItemOptionEntity->getSku())
            ->select(SpyProductOptionValueTableMap::COL_ID_PRODUCT_OPTION_VALUE)
            ->findOne();
    }
}
