<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class GroupKeyExpander
{

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expand(CartChangeTransfer $change)
    {
        foreach ($change->getItems() as $item) {
            $item->setGroupKey($this->buildGroupKey($item));
        }

        return $change;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $cartItem
     *
     * @return string
     */
    protected function buildGroupKey(ItemTransfer $cartItem)
    {
        $currentGroupKey = $cartItem->getGroupKey();
        if (empty($cartItem->getProductOptions())) {
            return $currentGroupKey;
        }

        $sortedProductOptions = $this->sortOptions((array)$cartItem->getProductOptions());
        $optionGroupKey = $this->combineOptionParts($sortedProductOptions);

        if (empty($optionGroupKey)) {
            return $currentGroupKey;
        }

        return !empty($currentGroupKey) ? $currentGroupKey . '-' . $optionGroupKey : $optionGroupKey;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function sortOptions(array $options)
    {
        usort(
            $options,
            function (ProductOptionTransfer $productOptionLeft, ProductOptionTransfer $productOptionRight) {
                return ($productOptionLeft->getIdOptionValueUsage() < $productOptionRight->getIdOptionValueUsage()) ? -1 : 1;
            }
        );

        return $options;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer[] $sortedProductOptions
     *
     * @return string
     */
    protected function combineOptionParts(array $sortedProductOptions)
    {
        $groupKeyPart = [];
        foreach ($sortedProductOptions as $option) {
            if (empty($option->getIdOptionValueUsage())) {
                continue;
            }
            $groupKeyPart[] = $option->getIdOptionValueUsage();
        }

        return implode('-', $groupKeyPart);
    }

}
