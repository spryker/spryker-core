<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCartConnector\Business\Model;

use Generated\Shared\Cart\ChangeInterface;
use Generated\Shared\ProductOption\ProductOptionInterface;
use Generated\Shared\ProductOptionCartConnector\ItemInterface;

class GroupKeyExpander
{

    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function expand(ChangeInterface $change)
    {
        foreach ($change->getItems() as $item) {
            $item->setGroupKey($this->buildGroupKey($item));
        }

        return $change;
    }

    /**
     * @param ItemInterface $cartItem
     *
     * @return string
     */
    protected function buildGroupKey(ItemInterface $cartItem)
    {
        $currentGroupKey = $cartItem->getGroupKey();
        if (empty($cartItem->getProductOptions())) {
            return $currentGroupKey;
        }

        $sortedProductOptions = $this->sortOptions((array) $cartItem->getProductOptions());
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
            function (ProductOptionInterface $productOptionLeft, ProductOptionInterface $productOptionRight) {
                return ($productOptionLeft->getIdOptionValueUsage() < $productOptionRight->getIdOptionValueUsage()) ? -1 : 1;
            }
        );

        return $options;
    }

    /**
     * @param ProductOptionInterface[] $sortedProductOptions
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
