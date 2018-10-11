<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi;

use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCustomerClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartCreator;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartCreatorInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartDeleter;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartDeleterInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReader;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\GuestCartCreator;
use Spryker\Glue\CartsRestApi\Processor\Cart\GuestCartCreatorInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemAdder;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemAdderInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemDeleter;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemDeleterInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemUpdater;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemUpdaterInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItem\GuestCartItemAdder;
use Spryker\Glue\CartsRestApi\Processor\CartItem\GuestCartItemAdderInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItem\GuestCartItemDeleter;
use Spryker\Glue\CartsRestApi\Processor\CartItem\GuestCartItemDeleterInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapper;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapper;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Shared\Kernel\Store;

class CartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface
     */
    public function createCartReader(): CartReaderInterface
    {
        return new CartReader(
            $this->getResourceBuilder(),
            $this->createCartsResourceMapper(),
            $this->getQuoteCollectionReaderPlugin(),
            $this->getCustomerClient()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Cart\CartCreatorInterface
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
     * @return \Spryker\Glue\CartsRestApi\Processor\Cart\CartDeleterInterface
     */
    public function createCartDeleter(): CartDeleterInterface
    {
        return new CartDeleter(
            $this->getResourceBuilder(),
            $this->getPersistentCartClient(),
            $this->createCartReader()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemAdderInterface
     */
    public function createCartItemAdder(): CartItemAdderInterface
    {
        return new CartItemAdder(
            $this->getCartClient(),
            $this->getResourceBuilder(),
            $this->getZedRequestClient(),
            $this->getQuoteClient(),
            $this->createCartReader(),
            $this->createCartItemsResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemDeleterInterface
     */
    public function createCartItemDeleter(): CartItemDeleterInterface
    {
        return new CartItemDeleter(
            $this->getCartClient(),
            $this->getResourceBuilder(),
            $this->getQuoteClient(),
            $this->createCartReader()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemUpdaterInterface
     */
    public function createCartItemUpdater(): CartItemUpdaterInterface
    {
        return new CartItemUpdater(
            $this->getCartClient(),
            $this->getResourceBuilder(),
            $this->getZedRequestClient(),
            $this->getQuoteClient(),
            $this->createCartReader()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\CartItem\GuestCartItemDeleterInterface
     */
    public function createGuestCartItemDeleter(): GuestCartItemDeleterInterface
    {
        return new GuestCartItemDeleter(
            $this->getCartClient(),
            $this->getResourceBuilder(),
            $this->getQuoteClient(),
            $this->createCartReader()
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

    /**
     * @return \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCustomerClientInterface
     */
    public function getCustomerClient(): CartsRestApiToCustomerClientInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\CartItem\GuestCartItemAdderInterface
     */
    public function createGuestCartItemAdder(): GuestCartItemAdderInterface
    {
        return new GuestCartItemAdder(
            $this->getCartClient(),
            $this->getResourceBuilder(),
            $this->getZedRequestClient(),
            $this->getQuoteClient(),
            $this->createCartReader(),
            $this->createGuestCartCreator(),
            $this->createCartItemsResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Cart\GuestCartCreatorInterface
     */
    public function createGuestCartCreator(): GuestCartCreatorInterface
    {
        return new GuestCartCreator(
            $this->getResourceBuilder(),
            $this->createCartsResourceMapper(),
            $this->getPersistentCartClient(),
            $this->getStorage()
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStorage(): Store
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::STORE);
    }
}
