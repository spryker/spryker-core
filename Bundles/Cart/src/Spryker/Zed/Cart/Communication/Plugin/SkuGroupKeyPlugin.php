<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Communication\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Cart\Business\CartFacadeInterface getFacade()
 * @method \Spryker\Zed\Cart\Communication\CartCommunicationFactory getFactory()
 */
class SkuGroupKeyPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    public const GROUP_KEY_DELIMITER = '_';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer)
    {
        foreach ($cartChangeTransfer->getItems() as $cartItem) {
            $cartItem->setGroupKey($this->buildGroupKey($cartItem));
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $cartItem
     *
     * @return string
     */
    protected function buildGroupKey(ItemTransfer $cartItem)
    {
        if ($cartItem->getGroupKeyPrefix()) {
            return $cartItem->getGroupKeyPrefix() . static::GROUP_KEY_DELIMITER . $cartItem->getSku();
        }

        return $cartItem->getSku();
    }
}
