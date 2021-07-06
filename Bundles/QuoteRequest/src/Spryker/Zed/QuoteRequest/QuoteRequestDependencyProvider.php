<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCalculationFacadeBridge;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCartFacadeBridge;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserFacadeBridge;
use Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\QuoteRequest\QuoteRequestConfig getConfig()
 */
class QuoteRequestDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';
    public const FACADE_CALCULATION = 'FACADE_CALCULATION';
    public const FACADE_CART = 'FACADE_CART';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const PLUGINS_QUOTE_REQUEST_QUOTE_CHECK = 'PLUGINS_QUOTE_REQUEST_QUOTE_CHECK';
    public const PLUGINS_QUOTE_REQUEST_VALIDATOR = 'PLUGINS_QUOTE_REQUEST_VALIDATOR';
    public const PLUGINS_QUOTE_REQUEST_USER_VALIDATOR = 'PLUGINS_QUOTE_REQUEST_USER_VALIDATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCompanyUserFacade($container);
        $container = $this->addCalculationFacade($container);
        $container = $this->addCartFacade($container);
        $container = $this->addQuoteRequestPreCreateCheckPlugins($container);
        $container = $this->addQuoteRequestValidatorPlugins($container);
        $container = $this->addQuoteRequestUserValidatorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_USER, function (Container $container) {
            return new QuoteRequestToCompanyUserFacadeBridge($container->getLocator()->companyUser()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCalculationFacade(Container $container): Container
    {
        $container->set(static::FACADE_CALCULATION, function (Container $container) {
            return new QuoteRequestToCalculationFacadeBridge($container->getLocator()->calculation()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartFacade(Container $container): Container
    {
        $container->set(static::FACADE_CART, function (Container $container) {
            return new QuoteRequestToCartFacadeBridge($container->getLocator()->cart()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new QuoteRequestToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteRequestPreCreateCheckPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUOTE_REQUEST_QUOTE_CHECK, function () {
            return $this->getQuoteRequestPreCreateCheckPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\QuoteRequestExtension\Dependency\Plugin\QuoteRequestPreCreateCheckPluginInterface[]
     */
    protected function getQuoteRequestPreCreateCheckPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteRequestValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUOTE_REQUEST_VALIDATOR, function (): array {
            return $this->getQuoteRequestValidatorPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\QuoteRequestExtension\Dependency\Plugin\QuoteRequestValidatorPluginInterface[]
     */
    protected function getQuoteRequestValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteRequestUserValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUOTE_REQUEST_USER_VALIDATOR, function (): array {
            return $this->getQuoteRequestUserValidatorPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\QuoteRequestExtension\Dependency\Plugin\QuoteRequestUserValidatorPluginInterface[]
     */
    protected function getQuoteRequestUserValidatorPlugins(): array
    {
        return [];
    }
}
