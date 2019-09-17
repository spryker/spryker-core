<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Communication\Plugin\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\PostSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ConfigurableBundleCart\Business\ConfigurableBundleCartFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleCart\ConfigurableBundleCartConfig getConfig()
 */
class ConfiguredBundleQuantityPostSavePlugin extends AbstractPlugin implements PostSavePluginInterface
{
    /**
     * {@inheritdoc}
     * - TODO:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function postSave(QuoteTransfer $quoteTransfer)
    {
        // TODO: move to Facade -> Business Model.

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getConfiguredBundle() || !$itemTransfer->getConfiguredBundleItem()) {
                continue;
            }

            $itemTransfer->getConfiguredBundle()->setQuantity(
                (int)($itemTransfer->getQuantity() / $itemTransfer->getConfiguredBundleItem()->getQuantityPerSlot())
            );
        }

        return $quoteTransfer;
    }
}
