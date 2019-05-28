<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct\DataReader;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToQuoteClientInterface;

class PriceEnvironmentReader implements PriceEnvironmentReaderInterface
{
    /**
     * @var \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @var \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @var \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface $priceClient
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface $currencyClient
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToQuoteClientInterface $quoteClient
     */
    public function __construct(
        PriceProductToPriceClientInterface $priceClient,
        PriceProductToCurrencyClientInterface $currencyClient,
        PriceProductToQuoteClientInterface $quoteClient
    ) {
        $this->priceClient = $priceClient;
        $this->currencyClient = $currencyClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @return string
     */
    public function getCurrentPriceMode(): string
    {
        return $this->priceClient->getCurrentPriceMode();
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrentCurrency(): CurrencyTransfer
    {
        return $this->currencyClient->getCurrent();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getCurrentQuote(): QuoteTransfer
    {
        return $this->quoteClient->getQuote();
    }
}
