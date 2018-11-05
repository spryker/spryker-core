<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi;

use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientBridge;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientBridge;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientBridge;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientBridge;
use Spryker\Glue\CartsRestApi\Exception\MissingQuoteCollectionReaderPluginException;
use Spryker\Glue\CartsRestApi\Exception\MissingQuoteCreatorPluginException;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class CartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const CLIENT_PERSISTENT_CART = 'CLIENT_PERSISTENT_CART';
    public const PLUGIN_QUOTE_COLLECTION_READER = 'PLUGIN_QUOTE_COLLECTION_READER';
    public const PLUGIN_QUOTE_CREATOR = 'PLUGIN_QUOTE_CREATOR';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addCartClient($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addPersistentCartClient($container);
        $container = $this->addQuoteCollectionReaderPlugin($container);
        $container = $this->addQuoteCreatorPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container[static::CLIENT_CART] = function (Container $container) {
            return new CartsRestApiToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container[static::CLIENT_ZED_REQUEST] = function (Container $container) {
            return new CartsRestApiToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container[static::CLIENT_QUOTE] = function (Container $container) {
            return new CartsRestApiToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addPersistentCartClient(Container $container): Container
    {
        $container[static::CLIENT_PERSISTENT_CART] = function (Container $container) {
            return new CartsRestApiToPersistentCartClientBridge($container->getLocator()->persistentCart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addQuoteCollectionReaderPlugin(Container $container): Container
    {
        $container[static::PLUGIN_QUOTE_COLLECTION_READER] = function () {
            return $this->getQuoteCollectionReaderPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addQuoteCreatorPlugin(Container $container): Container
    {
        $container[static::PLUGIN_QUOTE_CREATOR] = function () {
            return $this->getQuoteCreatorPlugin();
        };

        return $container;
    }

    /**
     * @throws \Spryker\Glue\CartsRestApi\Exception\MissingQuoteCollectionReaderPluginException
     *
     * @return \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
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
     * @throws \Spryker\Glue\CartsRestApi\Exception\MissingQuoteCreatorPluginException
     *
     * @return \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface
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
