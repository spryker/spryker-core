<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;

class ProductBundlePriceCalculation implements ProductBundlePriceCalculationInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $orderItems = SpySalesOrderItemQuery::create()
            ->findByFkSalesOrder($orderTransfer->getIdSalesOrder());

        $bundledProducts = [];
        foreach ($orderItems as $orderItemEntity) {
            if (!$orderItemEntity->getFkSalesOrderItemBundle()) {
                continue;
            }

            foreach ($orderTransfer->getItems() as $itemTransfer) {
                if ($itemTransfer->getIdSalesOrderItem() !== $orderItemEntity->getIdSalesOrderItem()) {
                    continue;
                }

                if (!isset($bundledProducts[$orderItemEntity->getFkSalesOrderItemBundle()])) {
                    $bundleItemTransfer = new ItemTransfer();
                    $bundleItemTransfer->setBundleItemIdentifier($orderItemEntity->getFkSalesOrderItemBundle());
                    $bundleItemTransfer->fromArray($orderItemEntity->getSalesOrderItemBundle()->toArray(), true);
                    $bundledProducts[$orderItemEntity->getFkSalesOrderItemBundle()] = $bundleItemTransfer;
                }

                $bundleItemTransfer = $bundledProducts[$orderItemEntity->getFkSalesOrderItemBundle()];

                $itemTransfer->setRelatedBundleItemIdentifier($orderItemEntity->getFkSalesOrderItemBundle());

                $this->addAmounts($bundleItemTransfer, $itemTransfer);

            }
        }

        $orderTransfer->setBundleItems(new \ArrayObject($bundledProducts));

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
                $this->addAmounts($bundleItemTransfer, $itemTransfer);

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
    protected function addAmounts(ItemTransfer $bundleItemTransfer, ItemTransfer $itemTransfer)
    {
        $bundleItemTransfer->setUnitGrossPrice(
            $bundleItemTransfer->getUnitGrossPrice() + $itemTransfer->getUnitGrossPrice()
        );

        $bundleItemTransfer->setSumGrossPrice(
            $bundleItemTransfer->getSumGrossPrice() + $itemTransfer->getSumGrossPrice()
        );

        $bundleItemTransfer->setUnitItemTotal(
            $bundleItemTransfer->getUnitItemTotal() + $itemTransfer->getUnitItemTotal()
        );

        $bundleItemTransfer->setSumItemTotal(
            $bundleItemTransfer->getSumItemTotal() + $itemTransfer->getSumItemTotal()
        );

        $bundleItemTransfer->setFinalUnitDiscountAmount(
            $bundleItemTransfer->getFinalUnitDiscountAmount() + $itemTransfer->getFinalUnitDiscountAmount()
        );

        $bundleItemTransfer->setFinalSumDiscountAmount(
            $bundleItemTransfer->getFinalSumDiscountAmount() + $itemTransfer->getFinalSumDiscountAmount()
        );
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
            $bundleItemTransfer->setFinalUnitDiscountAmount(0);
            $bundleItemTransfer->setFinalSumDiscountAmount(0);

        }
    }

}
