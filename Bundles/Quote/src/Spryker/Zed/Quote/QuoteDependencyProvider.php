<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeBridge;
use Spryker\Zed\Quote\Dependency\Service\QuoteToUtilEncodingServiceBridge;

class QuoteDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_STORE = 'FACADE_STORE';
    public const PLUGINS_QUOTE_CREATE_AFTER = 'PLUGINS_QUOTE_CREATE_AFTER';
    public const PLUGINS_QUOTE_CREATE_BEFORE = 'PLUGINS_QUOTE_CREATE_BEFORE';
    public const PLUGINS_QUOTE_UPDATE_AFTER = 'PLUGINS_QUOTE_UPDATE_AFTER';
    public const PLUGINS_QUOTE_UPDATE_BEFORE = 'PLUGINS_QUOTE_UPDATE_BEFORE';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const PLUGINS_QUOTE_DELETE_BEFORE = 'PLUGINS_QUOTE_DELETE_BEFORE';
    public const PLUGINS_QUOTE_DELETE_AFTER = 'PLUGINS_QUOTE_DELETE_AFTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addStoreFacade($container);
        $container = $this->addQuoteCreateAfterPlugins($container);
        $container = $this->addQuoteCreateBeforePlugins($container);
        $container = $this->addQuoteUpdateAfterPlugins($container);
        $container = $this->addQuoteUpdateBeforePlugins($container);
        $container = $this->addQuoteDeleteBeforePlugins($container);
        $container = $this->addQuoteDeleteAfterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container[self::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new QuoteToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container[self::FACADE_STORE] = function (Container $container) {
            return new QuoteToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteCreateAfterPlugins(Container $container): Container
    {
        $container[self::PLUGINS_QUOTE_CREATE_AFTER] = function (Container $container) {
            return $this->getQuoteCreateAfterPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteCreateBeforePlugins(Container $container): Container
    {
        $container[self::PLUGINS_QUOTE_CREATE_BEFORE] = function (Container $container) {
            return $this->getQuoteCreateBeforePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteUpdateAfterPlugins(Container $container): Container
    {
        $container[self::PLUGINS_QUOTE_UPDATE_AFTER] = function (Container $container) {
            return $this->getQuoteUpdateAfterPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteUpdateBeforePlugins(Container $container): Container
    {
        $container[self::PLUGINS_QUOTE_UPDATE_BEFORE] = function (Container $container) {
            return $this->getQuoteUpdateBeforePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteDeleteBeforePlugins(Container $container): Container
    {
        $container[self::PLUGINS_QUOTE_DELETE_BEFORE] = function (Container $container) {
            return $this->getQuoteDeleteBeforePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteDeleteAfterPlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUOTE_DELETE_AFTER] = function (Container $container) {
            return $this->getQuoteDeleteAfterPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected function getQuoteCreateAfterPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected function getQuoteCreateBeforePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected function getQuoteUpdateAfterPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected function getQuoteUpdateBeforePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected function getQuoteDeleteBeforePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteDeleteAfterPluginInterface[]
     */
    protected function getQuoteDeleteAfterPlugins(): array
    {
        return [];
    }
}
