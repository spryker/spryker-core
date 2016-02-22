<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Communication\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\Cart\Business\CartFacade getFacade()
 * @method \Spryker\Zed\Cart\Communication\CartCommunicationFactory getFactory()
 */
class SkuGroupKeyPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
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
     * @param \Generated\Shared\Transfer\ItemTransfer $cartItem
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
