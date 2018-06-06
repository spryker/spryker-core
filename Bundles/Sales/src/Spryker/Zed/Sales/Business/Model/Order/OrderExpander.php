<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationInterface;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationInterface
     */
    protected $calculationFacade;

    /**
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemExpanderPluginInterface[]
     */
    protected $salesOrderItemExpanderPlugins;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationInterface $calculationFacade
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemExpanderPluginInterface[] $salesOrderItemExpanderPlugins
     */
    public function __construct(SalesToCalculationInterface $calculationFacade, array $salesOrderItemExpanderPlugins)
    {
        $this->calculationFacade = $calculationFacade;
        $this->salesOrderItemExpanderPlugins = $salesOrderItemExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandSalesOrder(QuoteTransfer $quoteTransfer)
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($quoteTransfer->toArray(), true);
        $orderTransfer->setItems($this->expandItems($quoteTransfer->getItems()));

        $this->groupOrderDiscountsByGroupKey($orderTransfer->getItems());
        $orderTransfer = $this->calculationFacade->recalculateOrder($orderTransfer);

        $quoteTransfer->fromArray($orderTransfer->toArray(), true);

        return $quoteTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function expandItems(ArrayObject $items)
    {
        $expandedItems = new ArrayObject();
        foreach ($items as $itemTransfer) {
            $expandedItems = $this->expandItemsPerPlugin($expandedItems, $itemTransfer);
        }

        return $expandedItems;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $expandedItems
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function expandItemsPerPlugin(ArrayObject $expandedItems, ItemTransfer $itemTransfer): ArrayObject
    {
        foreach ($this->salesOrderItemExpanderPlugins as $salesOrderItemExpanderPlugin) {
            $expandedOrderItems = $salesOrderItemExpanderPlugin->expandOrderItem($itemTransfer);

            if ($expandedOrderItems == null) {
                continue;
            }

            foreach ($expandedOrderItems as $expandedOrderItem) {
                $expandedItems->append($expandedOrderItem);
            }

            return $expandedItems;
        }

        $expandedItems->append($itemTransfer);

        return $expandedItems;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItemCollection
     *
     * @return void
     */
    protected function groupOrderDiscountsByGroupKey(ArrayObject $orderItemCollection)
    {
        $calculatedItemDiscountsByGroupKey = [];
        $optionCalculatedDiscountsByGroupKey = [];
        foreach ($orderItemCollection as $orderItemTransfer) {
            if (!isset($calculatedItemDiscountsByGroupKey[$orderItemTransfer->getGroupKey()])) {
                $calculatedItemDiscountsByGroupKey[$orderItemTransfer->getGroupKey()] = (array)$orderItemTransfer->getCalculatedDiscounts();
            }
            $orderItemTransfer->setCalculatedDiscounts(
                $this->getGroupedCalculatedDiscounts($calculatedItemDiscountsByGroupKey, $orderItemTransfer->getGroupKey())
            );
            foreach ($orderItemTransfer->getProductOptions() as $productOptionTransfer) {
                if (!isset($optionCalculatedDiscountsByGroupKey[$orderItemTransfer->getGroupKey()])) {
                    $optionCalculatedDiscountsByGroupKey[$orderItemTransfer->getGroupKey()] = (array)$productOptionTransfer->getCalculatedDiscounts();
                }
                $productOptionTransfer->setCalculatedDiscounts(
                    $this->getGroupedCalculatedDiscounts($optionCalculatedDiscountsByGroupKey, $orderItemTransfer->getGroupKey())
                );
            }
        }
    }

    /**
     * @param array $calculatedDiscountsByGroupKey
     * @param string $groupKey
     *
     * @return \ArrayObject
     */
    protected function getGroupedCalculatedDiscounts(array &$calculatedDiscountsByGroupKey, $groupKey)
    {
        $discountCollection = $calculatedDiscountsByGroupKey[$groupKey];

        $appliedDiscounts = [];
        foreach ($discountCollection as $key => $discountTransfer) {
            $idDiscount = $discountTransfer->getIdDiscount();
            if (isset($appliedDiscounts[$idDiscount])) {
                continue;
            }

            $appliedDiscounts[$idDiscount] = $discountTransfer;
            unset($discountCollection[$key]);
        }
        $calculatedDiscountsByGroupKey[$groupKey] = $discountCollection;

        return new ArrayObject($appliedDiscounts);
    }
}
