<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Quote\Dependency\Client\QuoteToCurrencyClientBridge;
use Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientBridge;
use Spryker\Client\Quote\Dependency\Client\QuoteToStoreClientBridge;

/**
 * @method \Spryker\Client\Quote\QuoteConfig getConfig()
 */
class QuoteDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SESSION = 'session client';

    /**
     * @var string
     */
    public const QUOTE_TRANSFER_EXPANDER_PLUGINS = 'QUOTE_TRANSFER_EXPANDER_PLUGINS';

    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const SERVICE_ZED = 'SERVICE_ZED';

    /**
     * @var string
     */
    public const CLIENT_CURRENCY = 'CLIENT_CURRENCY';

    /**
     * @var string
     */
    public const PLUGINS_DATABASE_STRATEGY_PRE_CHECK_PLUGINS = 'PLUGINS_DATABASE_STRATEGY_PRE_CHECK_PLUGINS';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const PLUGINS_DATABASE_STRATEGY_READER_PLUGINS = 'PLUGINS_DATABASE_STRATEGY_READER_PLUGINS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addSessionClient($container);
        $container = $this->addQuoteTransferExpanderPlugins($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addZedSevice($container);
        $container = $this->addCurrencyClient($container);
        $container = $this->addDatabaseStrategyPreCheckPlugins($container);
        $container = $this->addStoreClient($container);
        $container = $this->addDatabaseStrategyReaderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSessionClient(Container $container)
    {
        $container->set(static::CLIENT_SESSION, function (Container $container) {
            return $container->getLocator()->session()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteTransferExpanderPlugins(Container $container)
    {
        $container->set(static::QUOTE_TRANSFER_EXPANDER_PLUGINS, function (Container $container) {
            return $this->getQuoteTransferExpanderPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addDatabaseStrategyPreCheckPlugins(Container $container)
    {
        $container->set(static::PLUGINS_DATABASE_STRATEGY_PRE_CHECK_PLUGINS, function () {
            return $this->getDatabaseStrategyPreCheckPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCustomerClient(Container $container)
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new QuoteToCustomerClientBridge($container->getLocator()->customer()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedSevice(Container $container)
    {
        $container->set(static::SERVICE_ZED, function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCurrencyClient(Container $container): Container
    {
        $container->set(static::CLIENT_CURRENCY, function (Container $container) {
            return new QuoteToCurrencyClientBridge($container->getLocator()->currency()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return array<\Spryker\Client\QuoteExtension\Dependency\Plugin\QuoteTransferExpanderPluginInterface>
     */
    protected function getQuoteTransferExpanderPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\QuoteExtension\Dependency\Plugin\DatabaseStrategyPreCheckPluginInterface>
     */
    protected function getDatabaseStrategyPreCheckPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new QuoteToStoreClientBridge($container->getLocator()->store()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addDatabaseStrategyReaderPlugins(Container $container)
    {
        $container->set(static::PLUGINS_DATABASE_STRATEGY_READER_PLUGINS, function () {
            return $this->getDatabaseStrategyReaderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\QuoteExtension\Dependency\Plugin\DatabaseStrategyReaderPluginInterface>
     */
    protected function getDatabaseStrategyReaderPlugins(): array
    {
        return [];
    }
}
