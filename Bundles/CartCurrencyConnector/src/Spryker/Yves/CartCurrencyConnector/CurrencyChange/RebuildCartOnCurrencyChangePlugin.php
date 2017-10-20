<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CartCurrencyConnector\CurrencyChange;

use Spryker\Yves\Currency\Dependency\CurrencyPostChangePluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 *
 * @method \Spryker\Yves\CartCurrencyConnector\CartCurrencyConnectorFactory getFactory()
 */
class RebuildCartOnCurrencyChangePlugin extends AbstractPlugin implements CurrencyPostChangePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function execute($currencyIsoCode)
    {
        $cartClient = $this->getFactory()->getCartClient();

        $quoteTransfer = $cartClient->getQuote();
        if (count($quoteTransfer->getItems()) > 0) {
            $cartClient->reloadItems();
        }
    }
}
