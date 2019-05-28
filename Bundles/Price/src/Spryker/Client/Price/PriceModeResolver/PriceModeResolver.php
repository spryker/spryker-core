<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price\PriceModeResolver;

use Spryker\Client\Price\Dependency\Client\PriceToQuoteClientInterface;
use Spryker\Client\Price\PriceConfig;
use Spryker\Client\Price\PriceModeCache\PriceModeCacheManagerInterface;

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
     * @var string|null
     */
    protected static $priceModeCache;

    /**
     * @var \Spryker\Client\Price\PriceModeCache\PriceModeCacheManagerInterface
     */
    protected $priceModeCacheManager;

    /**
     * @param \Spryker\Client\Price\Dependency\Client\PriceToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\Price\PriceConfig $priceConfig
     * @param \Spryker\Client\Price\PriceModeCache\PriceModeCacheManagerInterface $priceModeCacheManager
     */
    public function __construct(
        PriceToQuoteClientInterface $quoteClient,
        PriceConfig $priceConfig,
        PriceModeCacheManagerInterface $priceModeCacheManager
    ) {
        $this->quoteClient = $quoteClient;
        $this->priceConfig = $priceConfig;
        $this->priceModeCacheManager = $priceModeCacheManager;
    }

    /**
     * @return string
     */
    public function getCurrentPriceMode()
    {
        if (!$this->priceModeCacheManager->hasPriceModeCache()) {
            $quoteTransfer = $this->quoteClient->getQuote();

            $priceMode = $quoteTransfer->getPriceMode() ?: $this->priceConfig->getDefaultPriceMode();
            $this->priceModeCacheManager->cachePriceMode($priceMode);

            return $priceMode;
        }

        return $this->priceModeCacheManager->getPriceModeCache();
    }
}
