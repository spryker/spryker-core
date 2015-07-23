<?php

namespace SprykerFeature\Zed\Cart\Communication\Plugin;

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
            $cartItem->setGroupKey($cartItem->getSku());
        }

        return $change;
    }

}
