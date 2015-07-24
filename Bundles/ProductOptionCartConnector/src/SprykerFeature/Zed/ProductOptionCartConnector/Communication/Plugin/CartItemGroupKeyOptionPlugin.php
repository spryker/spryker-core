<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCartConnector\Communication\Plugin;

use Generated\Shared\Cart\ChangeInterface;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Generated\Shared\ProductOptionCartConnector\CartItemInterface;

class CartItemGroupKeyOptionPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function expandItems(ChangeInterface $change)
    {
        foreach ($change->getItems() as $item) {
            $item->setGroupKey($this->buildGroupKey($item));
        }

        return $change;
    }

    /**
     * @param CartItemInterface $cartItem
     *
     * @return string
     */
    protected function buildGroupKey(CartItemInterface $cartItem)
    {
        if (empty($cartItem->getProductOptions())) {
            return $cartItem->getGroupKey();
        }

        $groupKey = $cartItem->getGroupKey();

        $groupKeyPart = [];
        foreach ($cartItem->getProductOptions() as $option) {
            $groupKeyPart[] = $option->getIdOptionValueUsage();
        }
        $optionGroupKey = implode('_', $groupKeyPart);

        return !empty($groupKey) ? $groupKey . '_' . $optionGroupKey : $optionGroupKey;
    }
}
