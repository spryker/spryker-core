<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueTableMap;
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

            $itemTransfer->setUnitProductOptionPriceAggregation($salesOrderItemEntity->getProductOptionPriceAggregation());
            $itemTransfer->setSumProductOptionPriceAggregation($salesOrderItemEntity->getProductOptionPriceAggregation());

            foreach ($salesOrderItemEntity->getOptions() as $orderItemOptionEntity) {
                $productOptionsTransfer = $this->hydrateProductOptionTransfer($orderItemOptionEntity);
                $itemTransfer->addProductOption($productOptionsTransfer);
            }
        }

        return $orderTransfer;
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
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function hydrateProductOptionTransfer(SpySalesOrderItemOption $orderItemOptionEntity)
    {
        $productOptionsTransfer = new ProductOptionTransfer();
        $productOptionsTransfer->setQuantity(1);

        $productOptionsTransfer->setSumPrice($orderItemOptionEntity->getPrice());
        $productOptionsTransfer->setSumGrossPrice($orderItemOptionEntity->getGrossPrice());
        $productOptionsTransfer->setSumNetPrice($orderItemOptionEntity->getNetPrice());
        $productOptionsTransfer->setSumDiscountAmountAggregation($orderItemOptionEntity->getDiscountAmountAggregation());
        $productOptionsTransfer->setSumTaxAmount($orderItemOptionEntity->getTaxAmount());
        $productOptionsTransfer->fromArray($orderItemOptionEntity->toArray(), true);

        $idProductOptionsValue = $this->getIdProductOptionValue($orderItemOptionEntity);
        if ($idProductOptionsValue) {
            $productOptionsTransfer->setIdProductOptionValue($idProductOptionsValue);
        }

        return $productOptionsTransfer;
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
