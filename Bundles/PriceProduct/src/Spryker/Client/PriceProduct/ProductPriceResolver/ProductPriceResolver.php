<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct\ProductPriceResolver;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface;
use Spryker\Client\PriceProduct\PriceProductConfig;

class ProductPriceResolver implements ProductPriceResolverInterface
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
     * @var \Spryker\Client\Price\PriceConfig
     */
    protected $priceProductConfig;

    /**
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface $priceClient
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface $currencyClient
     * @param \Spryker\Client\PriceProduct\PriceProductConfig $priceProductConfig
     */
    public function __construct(
        PriceProductToPriceClientInterface $priceClient,
        PriceProductToCurrencyClientInterface $currencyClient,
        PriceProductConfig $priceProductConfig
    ) {
        $this->priceProductConfig = $priceProductConfig;
        $this->priceClient = $priceClient;
        $this->currencyClient = $currencyClient;
    }

    /**
     * @param array $priceMap
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolve(array $priceMap)
    {
        $currentProductPriceTransfer = (new CurrentProductPriceTransfer())
            ->setPrice(0);

        $currencyIsoCode = $this->currencyClient->getCurrent()->getCode();
        $currentPriceMode = $this->priceClient->getCurrentPriceMode();
        if (!isset($priceMap[$currencyIsoCode]) ||
            !isset($priceMap[$currencyIsoCode][$currentPriceMode])) {
            return $currentProductPriceTransfer;
        }

        $price = null;
        $prices = $priceMap[$currencyIsoCode][$currentPriceMode];
        $defaultProductPriceType = $this->getPriceProductConfig()->getPriceTypeDefaultName();
        if (isset($prices[$defaultProductPriceType])) {
            $price = $prices[$defaultProductPriceType];
        }

        return $currentProductPriceTransfer
            ->setPrice($price)
            ->setPrices($prices);
    }

    /**
     * @return \Spryker\Shared\PriceProduct\PriceProductConfig
     */
    protected function getPriceProductConfig()
    {
        return $this->priceProductConfig->createSharedPriceConfig();
    }
}
