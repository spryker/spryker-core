<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\Model\OrderTotalsAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class ItemProductOptionGrossPrice implements OrderAmountAggregatorInterface
{

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(SalesQueryContainerInterface $salesQueryContainer)
    {
        $this->salesQueryContainer = $salesQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $productOptions = $this->getHydratedSalesProductOptions($orderTransfer);

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $this->setProductOptionAmountDefaults($itemTransfer);
            if (array_key_exists($itemTransfer->getIdSalesOrderItem(), $productOptions) === false) {
                continue;
            }

            $itemProductOptions = new \ArrayObject($productOptions[$itemTransfer->getIdSalesOrderItem()]);
            $this->setProductOptionTotals($itemProductOptions, $itemTransfer);

            $itemTransfer->setProductOptions($itemProductOptions);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    protected function getHydratedSalesProductOptions(OrderTransfer $orderTransfer)
    {
        $salesOrderItems = $this->getSalesOrderItems($orderTransfer);

        $hydratedProductOptions = [];
        foreach ($salesOrderItems as $salesOrderItemEntity) {
            foreach ($salesOrderItemEntity->getOptions() as $productOptionEntity) {
                if (!isset($hydratedProductOptions[$productOptionEntity->getFkSalesOrderItem()])) {
                    $hydratedProductOptions[$productOptionEntity->getFkSalesOrderItem()] = [];
                }
                $productOptionTransfer = $this->hydrateProductOptionTransfer($productOptionEntity, $salesOrderItemEntity);
                $hydratedProductOptions[$productOptionEntity->getFkSalesOrderItem()][] = $productOptionTransfer;
            }
        }

        return $hydratedProductOptions;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOptionTransfer[] $itemProductOptions
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function setProductOptionTotals(\ArrayObject $itemProductOptions, ItemTransfer $itemTransfer)
    {
        $totalProductOptionGrossSum = 0;
        $totalProductOptionGrossUnit = 0;
        $totalOptionsRefundableAmount = 0;
        foreach ($itemProductOptions as $productOptionTransfer) {
            $productOptionTransfer->requireUnitGrossPrice()->requireQuantity();
            $productOptionTransfer->setSumGrossPrice(
                $productOptionTransfer->getUnitGrossPrice() * $productOptionTransfer->getQuantity()
            );

            $totalProductOptionGrossSum += $productOptionTransfer->getSumGrossPrice();
            $totalProductOptionGrossUnit += $productOptionTransfer->getUnitGrossPrice();
            $totalOptionsRefundableAmount += $productOptionTransfer->getRefundableAmount();
        }

        $itemTransfer->setUnitGrossPriceWithProductOptions($itemTransfer->getUnitGrossPrice() + $totalProductOptionGrossUnit);
        $itemTransfer->setSumGrossPriceWithProductOptions($itemTransfer->getSumGrossPrice() + $totalProductOptionGrossSum);
        $itemTransfer->setRefundableAmount($itemTransfer->getRefundableAmount() + $totalOptionsRefundableAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemOption $productOptionEntity
     *
     * @return int
     */
    protected function getRefundableAmount(
        ProductOptionTransfer $productOptionTransfer,
        SpySalesOrderItemOption $productOptionEntity
    ) {
        return ($productOptionEntity->getGrossPrice() * $productOptionTransfer->getQuantity()) - $productOptionEntity->getCanceledAmount();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemOption $productOptionEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function hydrateProductOptionTransfer(
        SpySalesOrderItemOption $productOptionEntity,
        SpySalesOrderItem $salesOrderItemEntity
    ) {
        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->fromArray($productOptionEntity->toArray(), true);
        $productOptionTransfer->setUnitGrossPrice($productOptionEntity->getGrossPrice());
        $productOptionTransfer->setQuantity($salesOrderItemEntity->getQuantity());

        $refundableAmount = $this->getRefundableAmount($productOptionTransfer, $productOptionEntity);
        $productOptionTransfer->setRefundableAmount($refundableAmount);

        return $productOptionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function setProductOptionAmountDefaults(ItemTransfer $itemTransfer)
    {
        $itemTransfer->setUnitGrossPriceWithProductOptions($itemTransfer->getUnitGrossPrice());
        $itemTransfer->setSumGrossPriceWithProductOptions($itemTransfer->getSumGrossPrice());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int[]
     */
    protected function getSaleOrderItemIds(OrderTransfer $orderTransfer)
    {
        $saleOrderItemIds = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $saleOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        return $saleOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getSalesOrderItems(OrderTransfer $orderTransfer)
    {
        $saleOrderItemIds = $this->getSaleOrderItemIds($orderTransfer);

        if (empty($saleOrderItemIds)) {
            return [];
        }

        return $this->salesQueryContainer
            ->querySalesOrderItem()
            ->filterByIdSalesOrderItem($saleOrderItemIds)
            ->find();
    }

}
