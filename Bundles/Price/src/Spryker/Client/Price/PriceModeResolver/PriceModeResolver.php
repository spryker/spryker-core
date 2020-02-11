<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price\PriceModeResolver;

use Spryker\Client\Price\Dependency\Client\PriceToQuoteClientInterface;
use Spryker\Client\Price\PriceConfig;
use Spryker\Client\Price\PriceModeCache\PriceModeCacheInterface;

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
     * @var \Spryker\Client\Price\PriceModeCache\PriceModeCacheInterface
     */
    protected $priceModeCache;

    /**
     * @param \Spryker\Client\Price\Dependency\Client\PriceToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\Price\PriceConfig $priceConfig
     * @param \Spryker\Client\Price\PriceModeCache\PriceModeCacheInterface $priceModeCache
     */
    public function __construct(
        PriceToQuoteClientInterface $quoteClient,
        PriceConfig $priceConfig,
        PriceModeCacheInterface $priceModeCache
    ) {
        $this->quoteClient = $quoteClient;
        $this->priceConfig = $priceConfig;
        $this->priceModeCache = $priceModeCache;
    }

    /**
     * @return string
     */
    public function getCurrentPriceMode()
    {
        if ($this->priceModeCache->isCached()) {
            return $this->priceModeCache->get();
        }

        $quoteTransfer = $this->quoteClient->getQuote();

        $priceMode = $quoteTransfer->getPriceMode() ?: $this->priceConfig->getDefaultPriceMode();
        $this->priceModeCache->cache($priceMode);

        return $priceMode;
    }
}
