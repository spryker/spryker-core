<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCurrencyConnector\CurrencyChange;

use Generated\Shared\Transfer\CurrencyTransfer;
use Spryker\Client\CurrencyExtension\Dependency\CurrencyPostChangePluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\CartCurrencyConnector\CartCurrencyConnectorFactory getFactory()
 */
class CartUpdateCurrencyOnCurrencyChangePlugin extends AbstractPlugin implements CurrencyPostChangePluginInterface
{
    /**
     * Specification:
     * - Set currency to cart on currency change.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currency
     *
     * @return bool
     */
    public function execute(CurrencyTransfer $currency): bool
    {
        $cartClient = $this->getFactory()->getCartClient();

        $quoteTransfer = $cartClient->getQuote();
        if ($quoteTransfer->getCurrency()->getCode() !== $currency->getCode()) {
            $quoteResponseTransfer = $cartClient->setQuoteCurrency($currency);

            return $quoteResponseTransfer->getIsSuccessful();
        }

        return true;
    }
}
