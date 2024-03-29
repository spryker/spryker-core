<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CartCurrencyConnector\CurrencyChange;

use Spryker\Yves\Currency\Dependency\CurrencyPostChangePluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @deprecated Use {@link \Spryker\Client\CartCurrencyConnector\CurrencyChange\CartUpdateCurrencyOnCurrencyChangePlugin} instead.
 *
 * @method \Spryker\Yves\CartCurrencyConnector\CartCurrencyConnectorFactory getFactory()
 */
class RebuildCartOnCurrencyChangePlugin extends AbstractPlugin implements CurrencyPostChangePluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $currencyCode
     *
     * @return bool
     */
    public function execute($currencyCode)
    {
        $cartClient = $this->getFactory()->getCartClient();

        $quoteTransfer = $cartClient->getQuote();
        if (count($quoteTransfer->getItems()) > 0) {
            $cartClient->reloadItems();

            $zedRequestClient = $this->getFactory()->getZedRequestClient();
            if (count($zedRequestClient->getResponsesErrorMessages()) > 0) {
                return false;
            }
        }

        return true;
    }
}
