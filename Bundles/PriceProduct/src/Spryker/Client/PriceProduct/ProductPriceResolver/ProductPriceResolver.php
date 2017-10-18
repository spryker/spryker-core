<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct\ProductPriceResolver;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Spryker\Client\PriceProduct\PriceProductConfig;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyInterface;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceInterface;

class ProductPriceResolver implements ProductPriceResolverInterface
{

    /**
     * @var \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceInterface
     */
    protected $priceClient;

    /**
     * @var \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyInterface
     */
    protected $currencyClient;

    /**
     * @var \Spryker\Client\Price\PriceConfig
     */
    protected $priceProductConfig;

    /**
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceInterface $priceClient
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyInterface $currencyClient
     * @param \Spryker\Client\PriceProduct\PriceProductConfig $priceProductConfig
     */
    public function __construct(
        PriceProductToPriceInterface $priceClient,
        PriceProductToCurrencyInterface $currencyClient,
        PriceProductConfig $priceProductConfig
    )
    {
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

        $price = 0;
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
