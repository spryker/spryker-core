<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
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

}
