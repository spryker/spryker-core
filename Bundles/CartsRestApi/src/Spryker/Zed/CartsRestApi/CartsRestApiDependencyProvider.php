<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi;

use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeBridge;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeBridge;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeBridge;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeBridge;
use Spryker\Zed\CartsRestApi\Exception\MissingQuoteCollectionReaderPluginException;
use Spryker\Zed\CartsRestApi\Exception\MissingQuoteCreatorPluginException;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CartsRestApi\CartsRestApiConfig getConfig()
 */
class CartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_QUOTE = 'FACADE_QUOTE';
    public const FACADE_PERSISTENT_CART = 'FACADE_PERSISTENT_CART';
    public const FACADE_CART = 'FACADE_CART';
    public const FACADE_STORE = 'FACADE_STORE';
    public const PROPEL_QUERY_QUOTE = 'PROPEL_QUERY_QUOTE';
    public const PLUGIN_QUOTE_COLLECTION_READER = 'PLUGIN_QUOTE_COLLECTION_READER';
    public const PLUGIN_QUOTE_CREATOR = 'PLUGIN_QUOTE_CREATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addQuoteFacade($container);
        $container = $this->addPersistentCartFacade($container);
        $container = $this->addCartFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addQuoteCollectionReaderPlugin($container);
        $container = $this->addQuoteCreatorPlugin($container);

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
        $container = $this->addQuotePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuotePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_QUOTE] = function () {
            return SpyQuoteQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteFacade(Container $container): Container
    {
        $container[static::FACADE_QUOTE] = function (Container $container) {
            return new CartsRestApiToQuoteFacadeBridge($container->getLocator()->quote()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPersistentCartFacade(Container $container): Container
    {
        $container[static::FACADE_PERSISTENT_CART] = function (Container $container) {
            return new CartsRestApiToPersistentCartFacadeBridge($container->getLocator()->persistentCart()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartFacade(Container $container): Container
    {
        $container[static::FACADE_CART] = function (Container $container) {
            return new CartsRestApiToCartFacadeBridge($container->getLocator()->cart()->facade());
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
        $container[static::FACADE_STORE] = function (Container $container) {
            return new CartsRestApiToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteCollectionReaderPlugin(Container $container): Container
    {
        $container[static::PLUGIN_QUOTE_COLLECTION_READER] = function () {
            return $this->getQuoteCollectionReaderPlugin();
        };

        return $container;
    }

    /**
     * @throws \Spryker\Zed\CartsRestApi\Exception\MissingQuoteCollectionReaderPluginException
     *
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected function getQuoteCollectionReaderPlugin(): QuoteCollectionReaderPluginInterface
    {
        throw new MissingQuoteCollectionReaderPluginException(sprintf(
            'Missing instance of %s! You need to configure QuoteCollectionReaderPlugin ' .
            'in your own CartsRestApiDependencyProvider::getQuoteCollectionReaderPlugin() ' .
            'to be able to read quote collection.',
            QuoteCollectionReaderPluginInterface::class
        ));
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteCreatorPlugin(Container $container): Container
    {
        $container[static::PLUGIN_QUOTE_CREATOR] = function () {
            return $this->getQuoteCreatorPlugin();
        };

        return $container;
    }

    /**
     * @throws \Spryker\Zed\CartsRestApi\Exception\MissingQuoteCreatorPluginException
     *
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface
     */
    protected function getQuoteCreatorPlugin(): QuoteCreatorPluginInterface
    {
        throw new MissingQuoteCreatorPluginException(sprintf(
            'Missing instance of %s! You need to configure QuoteCreatorPluginInterface ' .
            'in your own CartsRestApiDependencyProvider::getQuoteCreatorPlugin() ' .
            'to be able to create quote.',
            QuoteCreatorPluginInterface::class
        ));
    }
}
