<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi;

use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientBridge;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientBridge;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCustomerClientBridge;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToQuoteClientBridge;
use Spryker\Glue\CheckoutRestApi\Exception\ReaderNotImplementedException;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class CheckoutRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_QUOTE_COLLECTION_READER = 'PLUGIN_QUOTE_COLLECTION_READER';
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_CHECKOUT = 'CLIENT_CHECKOUT';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    protected const EXCEPTION_MESSAGE_READER_NOT_IMPLEMENTED = 'Reader not implemented on project level';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addQuoteCollectionReaderPlugin($container);
        $container = $this->addCartClient($container);
        $container = $this->addCheckoutClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addCustomerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addQuoteCollectionReaderPlugin(Container $container): Container
    {
        $container[static::PLUGIN_QUOTE_COLLECTION_READER] = function (Container $container) {
            return $this->getQuoteCollectionReaderPlugin();
        };

        return $container;
    }

    /**
     * @throws \Spryker\Glue\CheckoutRestApi\Exception\ReaderNotImplementedException
     *
     * @return \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected function getQuoteCollectionReaderPlugin(): QuoteCollectionReaderPluginInterface
    {
        throw new ReaderNotImplementedException(static::EXCEPTION_MESSAGE_READER_NOT_IMPLEMENTED);
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container[static::CLIENT_CART] = function (Container $container) {
            return new CheckoutRestApiToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCheckoutClient(Container $container): Container
    {
        $container[static::CLIENT_CHECKOUT] = function (Container $container) {
            return new CheckoutRestApiToCheckoutClientBridge($container->getLocator()->checkout()->client());
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
            return new CheckoutRestApiToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new CheckoutRestApiToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }
}
