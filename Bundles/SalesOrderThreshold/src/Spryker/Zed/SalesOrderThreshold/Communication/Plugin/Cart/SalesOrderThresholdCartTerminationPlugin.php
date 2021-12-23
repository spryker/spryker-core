<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartTerminationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdConfig getConfig()
 */
class SalesOrderThresholdCartTerminationPlugin extends AbstractPlugin implements CartTerminationPluginInterface
{
    /**
     * @var array<string>
     */
    protected const SUBSCRIBED_TERMINATION_EVENT_NAMES = [
        'add',
        'remove',
    ];

    /**
     * {@inheritDoc}
     * - Does nothing if `QuoteTransfer.totals` is not set.
     * - Requires `QuoteTransfer.currency` to be set.
     * - Finds applicable thresholds.
     * - Calculates diff between minimal order value threshold and order value amounts.
     * - Translates sales order threshold messages.
     * - Expands quote with sales order thresholds data.
     * - Never terminates the process.
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
        if (!$this->isSubscribedToTerminationEvent($terminationEventName)) {
            return false;
        }

        $this->getFacade()->expandQuoteWithSalesOrderThresholdValues($calculatedQuoteTransfer);

        return false;
    }

    /**
     * @param string $terminationEventName
     *
     * @return bool
     */
    protected function isSubscribedToTerminationEvent(string $terminationEventName): bool
    {
        return in_array($terminationEventName, static::SUBSCRIBED_TERMINATION_EVENT_NAMES, true);
    }
}
