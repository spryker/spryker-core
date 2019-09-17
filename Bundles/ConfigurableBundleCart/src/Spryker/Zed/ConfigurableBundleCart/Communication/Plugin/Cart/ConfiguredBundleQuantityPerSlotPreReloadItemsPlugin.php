<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Communication\Plugin\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\PreReloadItemsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ConfigurableBundleCart\Business\ConfigurableBundleCartFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleCart\ConfigurableBundleCartConfig getConfig()
 */
class ConfiguredBundleQuantityPerSlotPreReloadItemsPlugin extends AbstractPlugin implements PreReloadItemsPluginInterface
{
    /**
     * {@inheritdoc}
     * - TODO:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preReloadItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        // TODO: move to Facade -> Business Model.

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getConfiguredBundleItem() || !$itemTransfer->getConfiguredBundle()) {
                continue;
            }

            $itemTransfer->getConfiguredBundleItem()->setQuantityPerSlot(
                (int)($itemTransfer->getQuantity() / $itemTransfer->getConfiguredBundle()->getQuantity())
            );
        }

        return $quoteTransfer;
    }
}
