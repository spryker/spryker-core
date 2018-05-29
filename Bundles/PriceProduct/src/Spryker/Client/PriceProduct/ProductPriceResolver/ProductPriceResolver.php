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
     * @var \Spryker\Client\PriceProduct\PriceProductConfig
     */
    protected $priceProductConfig;

    /**
     * @var \Spryker\Client\PriceProduct\Dependency\Plugin\PriceDimensionPluginInterface[]
     */
    protected $priceDimensionPlugins;

    /**
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface $priceClient
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface $currencyClient
     * @param \Spryker\Client\PriceProduct\PriceProductConfig $priceProductConfig
     * @param \Spryker\Client\PriceProduct\Dependency\Plugin\PriceDimensionPluginInterface[] $priceDimensionPlugins
     */
    public function __construct(
        PriceProductToPriceClientInterface $priceClient,
        PriceProductToCurrencyClientInterface $currencyClient,
        PriceProductConfig $priceProductConfig,
        array $priceDimensionPlugins
    ) {
        $this->priceProductConfig = $priceProductConfig;
        $this->priceClient = $priceClient;
        $this->currencyClient = $currencyClient;
        $this->priceDimensionPlugins = $priceDimensionPlugins;
    }

    /**
     * @param array $priceMap
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolve(array $priceMap)
    {
        $currentProductPriceTransfer = new CurrentProductPriceTransfer();

        $currencyIsoCode = $this->currencyClient->getCurrent()->getCode();
        $currentPriceMode = $this->priceClient->getCurrentPriceMode();
        if (!isset($priceMap[$currencyIsoCode][$currentPriceMode])) {
            return $currentProductPriceTransfer;
        }

        $price = null;
        $prices = $priceMap[$currencyIsoCode][$currentPriceMode];
        $defaultProductPriceType = $this->priceProductConfig->getPriceTypeDefaultName();
        if (isset($prices[$defaultProductPriceType])) {
            $price = $prices[$defaultProductPriceType];
        }

        return $currentProductPriceTransfer
            ->setPrice($price)
            ->setPrices($prices);
    }

    /**
     * @param array $priceMap
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductAbstractPriceByPriceDimension(array $priceMap, int $idProductAbstract)
    {
        foreach ($this->priceDimensionPlugins as $priceDimensionPlugin) {
            $priceProductAbstractDimensionTransfer = $priceDimensionPlugin->findProductAbstractPrice($idProductAbstract);

            if ($priceProductAbstractDimensionTransfer) {
                $priceMap = array_replace_recursive($priceMap, $priceProductAbstractDimensionTransfer->getPrices());
            }
        }

        return $this->resolve($priceMap);
    }

    /**
     * @param array $priceMap
     * @param int $idProductAbstract
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductConcretePriceByPriceDimension(array $priceMap, int $idProductAbstract, int $idProductConcrete)
    {
        foreach ($this->priceDimensionPlugins as $priceDimensionPlugin) {
            $priceProductDimensionTransfer = $priceDimensionPlugin->findProductConcretePrice($idProductConcrete);

            if ($priceProductDimensionTransfer === null) {
                $priceProductDimensionTransfer = $priceDimensionPlugin->findProductAbstractPrice($idProductAbstract);
            }

            if ($priceProductDimensionTransfer) {
                $priceMap = array_replace_recursive($priceMap, $priceProductDimensionTransfer->getPrices());
            }
        }

        return $this->resolve($priceMap);
    }
}
