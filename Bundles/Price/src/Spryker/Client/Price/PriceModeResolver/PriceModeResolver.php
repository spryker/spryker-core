<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price\PriceModeResolver;

use Spryker\Client\Price\Dependency\Client\PriceToQuoteClientInterface;
use Spryker\Client\Price\PriceConfig;

class PriceModeResolver implements PriceModeResolverInterface
{
    /**
     * @var \Spryker\Client\Price\Dependency\Client\PriceToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\Price\PriceConfig
     */
    protected $priceConfig;

    /**
     * @param \Spryker\Client\Price\Dependency\Client\PriceToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\Price\PriceConfig $priceConfig
     */
    public function __construct(PriceToQuoteClientInterface $quoteClient, PriceConfig $priceConfig)
    {
        $this->quoteClient = $quoteClient;
        $this->priceConfig = $priceConfig;
    }

    /**
     * @return string
     */
    public function getCurrentPriceMode()
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        if ($quoteTransfer->getPriceMode()) {
            return $quoteTransfer->getPriceMode();
        }

        return $this->priceConfig->getDefaultPriceMode();
    }
}
