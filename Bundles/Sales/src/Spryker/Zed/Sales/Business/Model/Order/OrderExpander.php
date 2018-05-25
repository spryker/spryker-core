<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationInterface;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationInterface
     */
    protected $calculationFacade;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCalculationInterface $calculationFacade
     */
    public function __construct(SalesToCalculationInterface $calculationFacade)
    {
        $this->calculationFacade = $calculationFacade;
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
     //   return $items;
        $expandedItems = new ArrayObject();
        foreach ($items as $itemTransfer) {
            $quantity = $itemTransfer->getQuantity();
            for ($i = 1; $quantity >= $i; $i++) {
                $expandedItemTransfer = new ItemTransfer();
                $expandedItemTransfer->fromArray($itemTransfer->toArray(), true);
                $expandedItemTransfer->setQuantity(1);

                $expandedProductOptions = new ArrayObject();
                foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                    $expandedProductOptions->append($this->copyProductOptionTransfer($productOptionTransfer));
                }

                $expandedItemTransfer->setProductOptions($expandedProductOptions);
                $expandedItems->append($expandedItemTransfer);
            }
        }

        return $expandedItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function copyProductOptionTransfer(ProductOptionTransfer $productOptionTransfer)
    {
        $expandedProductOptionTransfer = new ProductOptionTransfer();
        $expandedProductOptionTransfer->fromArray($productOptionTransfer->toArray(), true);
        $expandedProductOptionTransfer->setQuantity(1);
        $expandedProductOptionTransfer->setIdProductOptionValue(null);

        return $expandedProductOptionTransfer;
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
