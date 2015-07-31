<?php

namespace SprykerFeature\Zed\Cart\Communication\Plugin;

use Generated\Shared\Cart\ItemInterface;
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
     * @param ItemInterface $cartItem
     *
     * @return string
     */
    protected function buildGroupKey(ItemInterface $cartItem)
    {
        $groupKey = $cartItem->getGroupKey();
        if (empty($groupKey)) {
            return $cartItem->getSku();
        }

        $groupKey = $groupKey . '-' . $cartItem->getSku();

        return $groupKey;

    }
}
