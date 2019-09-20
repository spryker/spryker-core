<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartTerminationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ConfigurableBundleCart\Business\ConfigurableBundleCartFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleCart\ConfigurableBundleCartConfig getConfig()
 */
class ConfiguredBundleQuantityTerminationPlugin extends AbstractPlugin implements CartTerminationPluginInterface
{
    protected const SUBSCRIBED_TERMINATION_EVENTS = [
        'add',
        'remove',
    ];

    /**
     * {@inheritdoc}
     * - Terminates add/remove product to the cart process if configured bundle quantity is not proportional to product quantity.
     *
     * @api
     *
     * @param string $terminationEventName
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $calculatedQuoteTransfer
     *
     * @return bool
     */
    public function isTerminated(string $terminationEventName, CartChangeTransfer $cartChangeTransfer, QuoteTransfer $calculatedQuoteTransfer): bool
    {
        if (!$this->isSubscribedToTerminationEventName($terminationEventName)) {
            return false;
        }

        return !$this->getFacade()->checkConfiguredBundleQuantityInQuote($calculatedQuoteTransfer);
    }

    /**
     * @param string $terminationEventName
     *
     * @return bool
     */
    protected function isSubscribedToTerminationEventName(string $terminationEventName): bool
    {
        return in_array($terminationEventName, static::SUBSCRIBED_TERMINATION_EVENTS);
    }
}
