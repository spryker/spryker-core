<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp;

use Spryker\Shared\TaxApp\Dependency\Service\TaxAppToUtilEncodingServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToMessageBrokerFacadeBridge;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToOauthClientFacadeBridge;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToSalesFacadeBridge;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeBridge;

/**
 * @method \Spryker\Zed\TaxApp\TaxAppConfig getConfig()
 */
class TaxAppDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_MESSAGE_BROKER = 'FACADE_MESSAGE_BROKER';

    /**
     * @var string
     */
    public const PLUGINS_CALCULABLE_OBJECT_TAX_APP_EXPANDER = 'PLUGINS_CALCULABLE_OBJECT_TAX_APP_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_ORDER_TAX_APP_EXPANDER = 'PLUGINS_ORDER_TAX_APP_EXPANDER';

    /**
     * string
     *
     * @var string
     */
    public const PLUGINS_FALLBACK_QUOTE_CALCULATION = 'PLUGINS_FALLBACK_QUOTE_CALCULATION';

    /**
     * string
     *
     * @var string
     */
    public const PLUGINS_FALLBACK_ORDER_CALCULATION = 'PLUGINS_FALLBACK_ORDER_CALCULATION';

    /**
     * @var string
     */
    public const CLIENT_TAX_APP = 'CLIENT_TAX_APP';

    /**
     * @var string
     */
    public const FACADE_OAUTH_CLIENT = 'FACADE_OAUTH_CLIENT';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addCalculableObjectTaxAppExpanderPlugins($container);
        $container = $this->addOrderTaxAppExpanderPlugins($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addMessageBrokerFacade($container);
        $container = $this->addTaxAppClient($container);
        $container = $this->addOauthClientFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addFallbackQuoteCalculationPlugins($container);
        $container = $this->addFallbackOrderCalculationPlugins($container);

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
    protected function addTaxAppClient(Container $container): Container
    {
        $container->set(static::CLIENT_TAX_APP, function (Container $container) {
            return $container->getLocator()->taxApp()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthClientFacade(Container $container): Container
    {
        $container->set(static::FACADE_OAUTH_CLIENT, function (Container $container) {
            return new TaxAppToOauthClientFacadeBridge($container->getLocator()->oauthClient()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return new TaxAppToSalesFacadeBridge($container->getLocator()->sales()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new TaxAppToStoreFacadeBridge($container->getLocator()->store()->facade());
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
            return new TaxAppToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessageBrokerFacade(Container $container): Container
    {
        $container->set(static::FACADE_MESSAGE_BROKER, function (Container $container) {
            return new TaxAppToMessageBrokerFacadeBridge($container->getLocator()->messageBroker()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCalculableObjectTaxAppExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CALCULABLE_OBJECT_TAX_APP_EXPANDER, function () {
            return $this->getCalculableObjectTaxAppExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderTaxAppExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_TAX_APP_EXPANDER, function () {
            return $this->getOrderTaxAppExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFallbackQuoteCalculationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_FALLBACK_QUOTE_CALCULATION, function () {
            return $this->getFallbackQuoteCalculationPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFallbackOrderCalculationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_FALLBACK_ORDER_CALCULATION, function () {
            return $this->getFallbackOrderCalculationPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\TaxAppExtension\Dependency\Plugin\CalculableObjectTaxAppExpanderPluginInterface>
     */
    protected function getCalculableObjectTaxAppExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\TaxAppExtension\Dependency\Plugin\OrderTaxAppExpanderPluginInterface>
     */
    protected function getOrderTaxAppExpanderPlugins(): array
    {
        return [];
    }

    /**
     * This calculation stack is executed as a fallback during quote recalculation when tax app is not configured or is disabled.
     * Please see the descriptions of those plugins in {@link \Spryker\Zed\Calculation\CalculationDependencyProvider::getQuoteCalculatorPluginStack}.
     * This plugin stack should include all plugins present between extracted tax calculation plugins. They will be executed instead of TaxAppCalculationPlugin.
     *
     * @return array<\Spryker\Zed\CalculationExtension\Dependency\Plugin\CalculationPluginInterface>
     */
    protected function getFallbackQuoteCalculationPlugins(): array
    {
        return [];
    }

    /**
     * This calculation stack is executed as a fallback during order recalculation when tax app is not configured or is disabled.
     * Please see the descriptions of those plugins in {@link \Spryker\Zed\Calculation\CalculationDependencyProvider::getOrderCalculatorPluginStack}.
     * This plugin stack should include all plugins present between extracted tax calculation plugins. They will be executed instead of TaxAppCalculationPlugin.
     *
     * @return array<\Spryker\Zed\CalculationExtension\Dependency\Plugin\CalculationPluginInterface>
     */
    protected function getFallbackOrderCalculationPlugins(): array
    {
        return [];
    }
}
