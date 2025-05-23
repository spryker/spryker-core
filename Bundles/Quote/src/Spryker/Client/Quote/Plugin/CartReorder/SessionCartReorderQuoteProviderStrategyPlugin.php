<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartReorderExtension\Dependency\Plugin\CartReorderQuoteProviderStrategyPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @deprecated Use {@link \Spryker\Client\Quote\Plugin\CartReorder\ResetItemsSessionCartReorderQuoteProviderStrategyPlugin} instead.
 *
 * @method \Spryker\Client\Quote\QuoteClientInterface getClient()
 */
class SessionCartReorderQuoteProviderStrategyPlugin extends AbstractPlugin implements CartReorderQuoteProviderStrategyPluginInterface
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_SESSION
     *
     * @var string
     */
    protected const STORAGE_STRATEGY_SESSION = 'session';

    /**
     * {@inheritDoc}
     * - Checks if the storage strategy is session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(CartReorderRequestTransfer $cartReorderRequestTransfer): bool
    {
        return $this->getClient()->getStorageStrategy() === static::STORAGE_STRATEGY_SESSION;
    }

    /**
     * {@inheritDoc}
     * - Gets the quote from the session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(CartReorderRequestTransfer $cartReorderRequestTransfer): QuoteTransfer
    {
        return $this->getClient()->getQuote();
    }
}
