<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CartCurrencyConnector\Dependency\Client;

interface CartCurrencyConnectorToCartClientInterface
{

    /**
     * @return void
     */
    public function rebuild();

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote();

}
