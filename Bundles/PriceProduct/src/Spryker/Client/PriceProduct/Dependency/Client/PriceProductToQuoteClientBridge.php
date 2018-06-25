<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct\Dependency\Client;

class PriceProductToQuoteClientBridge implements PriceProductToQuoteClientInterface
{
    /**
     * @var \Spryker\Client\Quote\QuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\Quote\QuoteClientInterface $quoteClient
     */
    public function __construct($quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        return $this->quoteClient->getQuote();
    }
}
