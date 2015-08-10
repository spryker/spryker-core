<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionWishlistConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Generated\Shared\ProductOption\ProductOptionInterface;
use Generated\Shared\ProductOptionCartConnector\ItemInterface;
use SprykerFeature\Zed\Wishlist\Dependency\PreSavePluginInterface;

class PreSaveGroupKeyProductOptionPlugin extends AbstractPlugin implements PreSavePluginInterface
{
    /**
     * @param \ArrayObject $items
     */
    public function trigger(\ArrayObject $items)
    {
        foreach ($items as $item) {
            $item->setGroupKey($this->buildGroupKey($item));
        }
    }

    /**
     * @param ItemInterface $item
     *
     * @return string
     */
    protected function buildGroupKey(ItemInterface $item)
    {
        $currentGroupKey = $item->getGroupKey();
        if (empty($item->getProductOptions())) {
            return $currentGroupKey;
        }

        $sortedProductOptions = $this->sortOptions((array) $item->getProductOptions());
        $optionGroupKey = $this->combineOptionParts($sortedProductOptions);

        if (empty($optionGroupKey)) {
            return $currentGroupKey;
        }

        return !empty($currentGroupKey) ? $currentGroupKey . '-' . $optionGroupKey : $optionGroupKey;
    }

    /**
     * @param ProductOptionInterface[] $options
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
