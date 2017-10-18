<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Price\PriceModeResolver;

use Spryker\Client\Quote\QuoteClientInterface;
use Spryker\Yves\Price\Dependency\Client\PriceToQuoteClientInterface;
use Spryker\Yves\Price\PriceConfig;

class PriceModeResolver implements PriceModeResolverInterface
{
    /**
     * @var \Spryker\Yves\Price\Dependency\Client\PriceToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Yves\Price\PriceConfig
     */
    protected $priceConfig;

    /**
     * @param \Spryker\Yves\Price\Dependency\Client\PriceToQuoteClientInterface $quoteClient
     * @param \Spryker\Yves\Price\PriceConfig $priceConfig
     */
    public function __construct(
        PriceToQuoteClientInterface $quoteClient,
        PriceConfig $priceConfig
    )
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

        return $this->priceConfig
            ->createSharedConfig()
            ->getDefaultPriceMode();
    }
}
