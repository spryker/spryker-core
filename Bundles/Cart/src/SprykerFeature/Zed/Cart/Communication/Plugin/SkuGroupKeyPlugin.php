<?php

namespace SprykerFeature\Zed\Cart\Communication\Plugin;

use Generated\Shared\Cart\CartItemInterface;
use Generated\Shared\Cart\ChangeInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;

class SkuGroupKeyPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function expandItems(ChangeInterface $change)
    {
        foreach ($change->getItems() as $cartItem) {
            $cartItem->setGroupKey($this->buildGroupKey($cartItem));
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
        $groupKey = $cartItem->getGroupKey();
        return !empty($groupKey) ? $groupKey . '-' . $cartItem->getSku() : $cartItem->getSku();
    }
}
