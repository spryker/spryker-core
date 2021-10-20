<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceProductOffer\Dependency\External\PriceProductOfferToValidationAdapter;
use Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeBridge;
use Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToStoreFacadeBridge;
use Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToTranslatorFacadeBridge;

/**
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 */
class PriceProductOfferDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';

    /**
     * @var string
     */
    public const EXTERNAL_ADAPTER_VALIDATION = 'EXTERNAL_ADAPTER_VALIDATION';

    /**
     * @var string
     */
    public const PLUGINS_PRICE_PRODUCT_OFFER_EXTRACTOR = 'PLUGINS_PRICE_PRODUCT_OFFER_EXTRACTOR';

    /**
     * @var string
     */
    public const PLUGINS_PRICE_PRODUCT_OFFER_EXPANDER = 'PLUGINS_PRICE_PRODUCT_OFFER_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRICE_PRODUCT_OFFER_VALIDATOR = 'PLUGINS_PRICE_PRODUCT_OFFER_VALIDATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addPriceProductFacade($container);
        $container = $this->addValidationAdapter($container);
        $container = $this->addStoreFacadeFacade($container);
        $container = $this->addTranslatorFacade($container);

        $container = $this->addPriceProductOfferExtractorPlugins($container);
        $container = $this->addPriceProductOfferExpanderPlugins($container);
        $container = $this->addPriceProductOfferValidatorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRICE_PRODUCT, function (Container $container) {
            return new PriceProductOfferToPriceProductFacadeBridge(
                $container->getLocator()->priceProduct()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacadeFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new PriceProductOfferToStoreFacadeBridge(
                $container->getLocator()->store()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addValidationAdapter(Container $container): Container
    {
        $container->set(static::EXTERNAL_ADAPTER_VALIDATION, function () {
            return new PriceProductOfferToValidationAdapter();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTranslatorFacade(Container $container): Container
    {
        $container->set(static::FACADE_TRANSLATOR, function (Container $container) {
            return new PriceProductOfferToTranslatorFacadeBridge(
                $container->getLocator()->translator()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductOfferExtractorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRICE_PRODUCT_OFFER_EXTRACTOR, function () {
            return $this->getPriceProductOfferExtractorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductOfferValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRICE_PRODUCT_OFFER_VALIDATOR, function () {
            return $this->getPriceProductOfferValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductOfferExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRICE_PRODUCT_OFFER_EXPANDER, function () {
            return $this->getPriceProductOfferExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferExtractorPluginInterface>
     */
    protected function getPriceProductOfferExtractorPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferExpanderPluginInterface>
     */
    protected function getPriceProductOfferExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferValidatorPluginInterface>
     */
    protected function getPriceProductOfferValidatorPlugins(): array
    {
        return [];
    }
}
