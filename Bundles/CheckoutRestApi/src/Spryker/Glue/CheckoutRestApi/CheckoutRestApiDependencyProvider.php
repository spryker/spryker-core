<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi;

use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientBridge;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientBridge;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToZedRequestClientBridge;
use Spryker\Glue\CheckoutRestApi\Exception\MissingQuoteCollectionReaderPluginException;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class CheckoutRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';
    public const PLUGIN_QUOTE_COLLECTION_READER = 'PLUGIN_QUOTE_COLLECTION_READER';

    protected const EXCEPTION_MESSAGE_READER_NOT_IMPLEMENTED = 'Reader not implemented on project level';

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
        $container = $this->addGlossaryStorageClient($container);
        $container = $this->addQuoteCollectionReaderPlugin($container);

        return $container;
    }

    /**
     * @throws \Spryker\Glue\CheckoutRestApi\Exception\MissingQuoteCollectionReaderPluginException
     *
     * @return \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected function getQuoteCollectionReaderPlugin(): QuoteCollectionReaderPluginInterface
    {
        throw new MissingQuoteCollectionReaderPluginException(sprintf(
            'Missing instance of %s! You need to configure QuoteCollectionReaderPlugin ' .
            'in your own CheckoutRestApiDependencyProvider::getQuoteCollectionReaderPlugin() ' .
            'to be able to read quote collection.',
            QuoteCollectionReaderPluginInterface::class
        ));
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
    protected function addZedRequestClient(Container $container): Container
    {
        $container[static::CLIENT_ZED_REQUEST] = function (Container $container) {
            return new CheckoutRestApiToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container[static::CLIENT_GLOSSARY_STORAGE] = function (Container $container) {
            return new CheckoutRestApiToGlossaryStorageClientBridge($container->getLocator()->glossaryStorage()->client());
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
        $container[static::PLUGIN_QUOTE_COLLECTION_READER] = function (Container $container) {
            return $this->getQuoteCollectionReaderPlugin();
        };

        return $container;
    }
}
