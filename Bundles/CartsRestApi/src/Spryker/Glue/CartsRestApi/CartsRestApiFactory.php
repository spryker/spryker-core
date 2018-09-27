<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi;

use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItems\CartItemAdder;
use Spryker\Glue\CartsRestApi\Processor\CartItems\CartItemAdderInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItems\CartItemDeleter;
use Spryker\Glue\CartsRestApi\Processor\CartItems\CartItemDeleterInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItems\CartItemUpdater;
use Spryker\Glue\CartsRestApi\Processor\CartItems\CartItemUpdaterInterface;
use Spryker\Glue\CartsRestApi\Processor\Carts\CartCreator;
use Spryker\Glue\CartsRestApi\Processor\Carts\CartCreatorInterface;
use Spryker\Glue\CartsRestApi\Processor\Carts\CartDeleter;
use Spryker\Glue\CartsRestApi\Processor\Carts\CartDeleterInterface;
use Spryker\Glue\CartsRestApi\Processor\Carts\CartReader;
use Spryker\Glue\CartsRestApi\Processor\Carts\CartReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapper;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapper;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Carts\CartReaderInterface
     */
    public function createCartsReader(): CartReaderInterface
    {
        return new CartReader(
            $this->getResourceBuilder(),
            $this->createCartsResourceMapper(),
            $this->getQuoteCollectionReaderPlugin()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Carts\CartCreatorInterface
     */
    public function createCartCreator(): CartCreatorInterface
    {
        return new CartCreator(
            $this->getResourceBuilder(),
            $this->createCartsResourceMapper(),
            $this->getPersistentCartClient()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Carts\CartDeleterInterface
     */
    public function createCartDeleter(): CartDeleterInterface
    {
        return new CartDeleter(
            $this->getResourceBuilder(),
            $this->getPersistentCartClient(),
            $this->createCartsReader()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\CartItems\CartItemAdderInterface
     */
    public function createCartItemAdder(): CartItemAdderInterface
    {
        return new CartItemAdder(
            $this->getCartClient(),
            $this->getResourceBuilder(),
            $this->getZedRequestClient(),
            $this->getQuoteClient(),
            $this->createCartsReader()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\CartItems\CartItemDeleterInterface
     */
    public function createCartItemDeleter(): CartItemDeleterInterface
    {
        return new CartItemDeleter(
            $this->getCartClient(),
            $this->getResourceBuilder(),
            $this->getQuoteClient(),
            $this->createCartsReader()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\CartItems\CartItemUpdaterInterface
     */
    public function createCartItemUpdater(): CartItemUpdaterInterface
    {
        return new CartItemUpdater(
            $this->getCartClient(),
            $this->getResourceBuilder(),
            $this->getZedRequestClient(),
            $this->getQuoteClient(),
            $this->createCartsReader()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface
     */
    public function createCartItemsResourceMapper(): CartItemsResourceMapperInterface
    {
        return new CartItemsResourceMapper();
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface
     */
    public function createCartsResourceMapper(): CartsResourceMapperInterface
    {
        return new CartsResourceMapper($this->createCartItemsResourceMapper(), $this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface
     */
    public function getZedRequestClient(): CartsRestApiToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface
     */
    public function getQuoteClient(): CartsRestApiToQuoteClientInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface
     */
    public function getCartClient(): CartsRestApiToCartClientInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface
     */
    public function getPersistentCartClient(): CartsRestApiToPersistentCartClientInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::CLIENT_PERSISTENT_CART);
    }

    /**
     * @return \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    public function getQuoteCollectionReaderPlugin(): QuoteCollectionReaderPluginInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::PLUGIN_QUOTE_COLLECTION_READER);
    }
}
