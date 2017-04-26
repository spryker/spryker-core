<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerInterface;

class ProductBundlePriceCalculation implements ProductBundlePriceCalculationInterface
{

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(ProductBundleToSalesQueryContainerInterface $salesQueryContainer)
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function calculate(QuoteTransfer $quoteTransfer)
    {
        $this->resetBundlePriceAmounts($quoteTransfer);

        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                if ($bundleItemTransfer->getBundleItemIdentifier() !== $itemTransfer->getRelatedBundleItemIdentifier()) {
                    continue;
                }
                $this->calculateBundleAmounts($bundleItemTransfer, $itemTransfer);
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function calculateBundleAmounts(ItemTransfer $bundleItemTransfer, ItemTransfer $itemTransfer)
    {
        $this->addPrice($bundleItemTransfer, $itemTransfer);
        $this->addNetPrice($bundleItemTransfer, $itemTransfer);
        $this->addGrossPrice($bundleItemTransfer, $itemTransfer);
        $this->addItemTotal($bundleItemTransfer, $itemTransfer);
        $this->addDiscounts($bundleItemTransfer, $itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function resetBundlePriceAmounts(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            $bundleItemTransfer->setUnitGrossPrice(0);
            $bundleItemTransfer->setSumGrossPrice(0);
            $bundleItemTransfer->setUnitItemTotal(0);
            $bundleItemTransfer->setSumItemTotal(0);
            $bundleItemTransfer->setUnitPrice(0);
            $bundleItemTransfer->setSumPrice(0);
            $bundleItemTransfer->setUnitNetPrice(0);
            $bundleItemTransfer->setSumNetPrice(0);
            $bundleItemTransfer->setFinalUnitDiscountAmount(0);
            $bundleItemTransfer->setFinalSumDiscountAmount(0);
            $bundleItemTransfer->setUnitDiscountAmountAggregation(0);
            $bundleItemTransfer->setSumDiscountAmountAggregation(0);
            $bundleItemTransfer->setUnitDiscountAmountFullAggregation(0);
            $bundleItemTransfer->setSumDiscountAmountFullAggregation(0);
        }
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

        $bundleItemTransfer->setUnitDiscountAmountFullAggregation(
            $bundleItemTransfer->getUnitDiscountAmountFullAggregation() + $itemTransfer->getUnitDiscountAmountFullAggregation()
        );

        $bundleItemTransfer->setSumDiscountAmountFullAggregation(
            $bundleItemTransfer->getSumDiscountAmountFullAggregation() + $itemTransfer->getSumDiscountAmountFullAggregation()
        );

        $bundleItemTransfer->setUnitDiscountAmountAggregation(
            $bundleItemTransfer->getUnitDiscountAmountAggregation() + $itemTransfer->getUnitDiscountAmountAggregation()
        );

        $bundleItemTransfer->setSumDiscountAmountAggregation(
            $bundleItemTransfer->getSumDiscountAmountAggregation() + $itemTransfer->getSumDiscountAmountAggregation()
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
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addPrice(ItemTransfer $bundleItemTransfer, ItemTransfer $itemTransfer)
    {
        $bundleItemTransfer->setUnitPrice(
            $bundleItemTransfer->getUnitPrice() + $itemTransfer->getUnitPrice()
        );

        $bundleItemTransfer->setSumPrice(
            $bundleItemTransfer->getSumPrice() + $itemTransfer->getSumPrice()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addNetPrice(ItemTransfer $bundleItemTransfer, ItemTransfer $itemTransfer)
    {
        $bundleItemTransfer->setUnitNetPrice(
            $bundleItemTransfer->getUnitNetPrice() + $itemTransfer->getUnitNetPrice()
        );

        $bundleItemTransfer->setSumNetPrice(
            $bundleItemTransfer->getSumNetPrice() + $itemTransfer->getSumNetPrice()
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
