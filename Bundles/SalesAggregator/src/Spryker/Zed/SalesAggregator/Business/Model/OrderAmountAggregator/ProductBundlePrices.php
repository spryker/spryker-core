<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class ProductBundlePrices implements OrderAmountAggregatorInterface
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
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $orderItems = $this->findOrderItemsByIdSalesOrder($orderTransfer->getIdSalesOrder());

        $bundledProducts = [];
        foreach ($orderItems as $salesOrderItemEntity) {
            if (!$salesOrderItemEntity->getFkSalesOrderItemBundle()) {
                continue;
            }

            $bundledProducts = $this->calculateForBundleItems($orderTransfer, $salesOrderItemEntity, $bundledProducts);
        }

        $orderTransfer->setBundleItems(new ArrayObject($bundledProducts));

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function calculateBundleAmounts(ItemTransfer $bundleItemTransfer, ItemTransfer $itemTransfer)
    {
        $this->addGrossPrice($bundleItemTransfer, $itemTransfer);
        $this->addItemTotal($bundleItemTransfer, $itemTransfer);
        $this->addDiscounts($bundleItemTransfer, $itemTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findOrderItemsByIdSalesOrder($idSalesOrder)
    {
        return $this->salesQueryContainer
            ->querySalesOrderItem()
            ->findByFkSalesOrder($idSalesOrder);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem $orderItemEntity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function mapBundledItemTransferFromSalesOrderItemEntity(SpySalesOrderItem $orderItemEntity)
    {
        $bundleItemTransfer = new ItemTransfer();
        $bundleItemTransfer->setBundleItemIdentifier($orderItemEntity->getFkSalesOrderItemBundle());
        $bundleItemTransfer->setQuantity($orderItemEntity->getQuantity());
        $bundleItemTransfer->fromArray($orderItemEntity->getSalesOrderItemBundle()->toArray(), true);

        return $bundleItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addDiscounts(ItemTransfer $bundleItemTransfer, ItemTransfer $itemTransfer)
    {
        $bundleItemTransfer->setFinalUnitDiscountAmount(
            $bundleItemTransfer->getFinalUnitDiscountAmount() + $itemTransfer->getFinalUnitDiscountAmount()
        );

        $bundleItemTransfer->setFinalSumDiscountAmount(
            $bundleItemTransfer->getFinalSumDiscountAmount() + $itemTransfer->getFinalSumDiscountAmount()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addItemTotal(ItemTransfer $bundleItemTransfer, ItemTransfer $itemTransfer)
    {
        $bundleItemTransfer->setUnitItemTotal(
            $bundleItemTransfer->getUnitItemTotal() + $itemTransfer->getUnitItemTotal()
        );

        $bundleItemTransfer->setSumItemTotal(
            $bundleItemTransfer->getSumItemTotal() + $itemTransfer->getSumItemTotal()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addGrossPrice(ItemTransfer $bundleItemTransfer, ItemTransfer $itemTransfer)
    {
        $bundleItemTransfer->setUnitGrossPrice(
            $bundleItemTransfer->getUnitGrossPrice() + $itemTransfer->getUnitGrossPrice()
        );

        $bundleItemTransfer->setSumGrossPrice(
            $bundleItemTransfer->getSumGrossPrice() + $itemTransfer->getSumGrossPrice()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem $salesOrderItemEntity
     * @param array|\Generated\Shared\Transfer\ItemTransfer[] $bundledProducts
     *
     * @return array
     */
    protected function calculateForBundleItems(
        OrderTransfer $orderTransfer,
        SpySalesOrderItem $salesOrderItemEntity,
        array $bundledProducts
    ) {

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIdSalesOrderItem() !== $salesOrderItemEntity->getIdSalesOrderItem()) {
                continue;
            }

            if (!isset($bundledProducts[$salesOrderItemEntity->getFkSalesOrderItemBundle()])) {
                $bundleItemTransfer = $this->mapBundledItemTransferFromSalesOrderItemEntity($salesOrderItemEntity);
                $bundledProducts[$salesOrderItemEntity->getFkSalesOrderItemBundle()] = $bundleItemTransfer;
            }

            $bundleItemTransfer = $bundledProducts[$salesOrderItemEntity->getFkSalesOrderItemBundle()];

            $itemTransfer->setRelatedBundleItemIdentifier($salesOrderItemEntity->getFkSalesOrderItemBundle());

            $this->calculateBundleAmounts($bundleItemTransfer, $itemTransfer);

        }

        return $bundledProducts;
    }

}
