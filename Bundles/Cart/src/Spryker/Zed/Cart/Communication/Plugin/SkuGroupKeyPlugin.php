<?php

namespace Spryker\Zed\Cart\Communication\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\Cart\Business\CartFacade;
use Spryker\Zed\Cart\Communication\CartCommunicationFactory;

/**
 * @method CartFacade getFacade()
 * @method CartCommunicationFactory getFactory()
 */
class SkuGroupKeyPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @param ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change)
    {
        foreach ($change->getItems() as $cartItem) {
            $cartItem->setGroupKey($this->buildGroupKey($cartItem));
        }

        return $change;
    }

    /**
     * @param ItemTransfer $cartItem
     *
     * @return string
     */
    protected function buildGroupKey(ItemTransfer $cartItem)
    {
        $groupKey = $cartItem->getGroupKey();
        if (empty($groupKey)) {
            return $cartItem->getSku();
        }

        return $groupKey;
    }

}
