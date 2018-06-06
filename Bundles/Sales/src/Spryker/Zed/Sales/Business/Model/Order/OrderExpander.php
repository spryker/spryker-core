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
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemTransformerPluginInterface[]
     */
    protected $salesOrderItemTransformerPlugins;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationInterface $calculationFacade
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemTransformerPluginInterface[] $salesOrderItemTransformerPlugins
     */
    public function __construct(SalesToCalculationInterface $calculationFacade, array $salesOrderItemTransformerPlugins)
    {
        $this->calculationFacade = $calculationFacade;
        $this->salesOrderItemTransformerPlugins = $salesOrderItemTransformerPlugins;
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
        $orderTransfer->setItems($this->transformItems($quoteTransfer->getItems()));

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
    protected function transformItems(ArrayObject $items)
    {
        $transformedItems = new ArrayObject();
        foreach ($items as $itemTransfer) {
            $transformedItems = $this->transformItemsPerPlugin($transformedItems, $itemTransfer);
        }

        return $transformedItems;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $transformedItems
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function transformItemsPerPlugin(ArrayObject $transformedItems, ItemTransfer $itemTransfer): ArrayObject
    {
        foreach ($this->salesOrderItemTransformerPlugins as $salesOrderItemTransformerPlugin) {
            if (!$salesOrderItemTransformerPlugin->isApplicable($itemTransfer)) {
                continue;
            }

            $transformedOrderItems = $salesOrderItemTransformerPlugin->transformOrderItem($itemTransfer);

            foreach ($transformedOrderItems as $transformedOrderItem) {
                $transformedItems->append($transformedOrderItem);
            }

            return $transformedItems;
        }

        $transformedItems->append($itemTransfer);

        return $transformedItems;
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
