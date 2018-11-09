<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartsRestApi;

use Spryker\Client\CartsRestApi\Dependency\Client\CartsRestApiToCartClientBridge;
use Spryker\Client\CartsRestApi\Exception\MissingQuoteCollectionReaderPluginException;
use Spryker\Client\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CartsRestApiDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_CART = 'CLIENT_CART';
    public const CART_QUOTE_COLLECTION_READER_PLUGIN = 'CART_QUOTE_COLLECTION_READER_PLUGIN';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addCartClient($container);
        $container = $this->addCartQuoteCollectionReaderPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container[static::CLIENT_CART] = function (Container $container) {
            return new CartsRestApiToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCartQuoteCollectionReaderPlugin(Container $container)
    {
        $container[self::CART_QUOTE_COLLECTION_READER_PLUGIN] = function () {
            return $this->getQuoteCollectionReaderPlugin();
        };

        return $container;
    }

    /**
     * @throws \Spryker\Client\CartsRestApi\Exception\MissingQuoteCollectionReaderPluginException
     *
     * @return \Spryker\Client\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected function getQuoteCollectionReaderPlugin(): QuoteCollectionReaderPluginInterface
    {
        throw new MissingQuoteCollectionReaderPluginException(sprintf(
            'Missing instance of %s! You need to configure CartQuoteCollectionReaderPlugin ' .
            'in your own CartsRestApiDependencyProvider::getQuoteCollectionReaderPlugin() ' .
            'to be able to read quote collection.',
            QuoteCollectionReaderPluginInterface::class
        ));
    }
}
