<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartsRestApi;

use Spryker\Client\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface;
use Spryker\Client\CartsRestApi\Reader\CartQuoteCollectionReader;
use Spryker\Client\CartsRestApi\Reader\CartQuoteCollectionReaderInterface;
use Spryker\Client\CartsRestApi\Reader\CartReader;
use Spryker\Client\CartsRestApi\Reader\CartReaderInterface;
use Spryker\Client\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CartsRestApi\Reader\CartQuoteCollectionReaderInterface
     */
    public function createCartQuoteCollectionReader(): CartQuoteCollectionReaderInterface
    {
        return new CartQuoteCollectionReader($this->getCartClient());
    }

    /**
     * @return \Spryker\Client\CartsRestApi\Reader\CartReaderInterface
     */
    public function createCartReader(): CartReaderInterface
    {
        return new CartReader($this->getCartQuoteCollectionReaderPlugin());
    }

    /**
     * @return \Spryker\Client\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface
     */
    public function getCartClient(): CartsRestApiToCartClientInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Spryker\Client\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    public function getCartQuoteCollectionReaderPlugin(): QuoteCollectionReaderPluginInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::CART_QUOTE_COLLECTION_READER_PLUGIN);
    }
}
