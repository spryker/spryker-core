<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi;

use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartCreator;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartCreatorInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartDeleter;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartDeleterInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReader;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemAdder;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemAdderInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemDeleter;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemDeleterInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemUpdater;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemUpdaterInterface;
use Spryker\Glue\CartsRestApi\Processor\GuestCart\AnonymousCustomerUniqueIdValidator;
use Spryker\Glue\CartsRestApi\Processor\GuestCart\AnonymousCustomerUniqueIdValidatorInterface;
use Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartCreator;
use Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartCreatorInterface;
use Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartReader;
use Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartUpdater;
use Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartUpdaterInterface;
use Spryker\Glue\CartsRestApi\Processor\GuestCartItem\GuestCartItemAdder;
use Spryker\Glue\CartsRestApi\Processor\GuestCartItem\GuestCartItemAdderInterface;
use Spryker\Glue\CartsRestApi\Processor\GuestCartItem\GuestCartItemDeleter;
use Spryker\Glue\CartsRestApi\Processor\GuestCartItem\GuestCartItemDeleterInterface;
use Spryker\Glue\CartsRestApi\Processor\GuestCartItem\GuestCartItemUpdater;
use Spryker\Glue\CartsRestApi\Processor\GuestCartItem\GuestCartItemUpdaterInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapper;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapper;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\Quote\QuoteCollectionReader;
use Spryker\Glue\CartsRestApi\Processor\Quote\QuoteCollectionReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\Quote\SingleQuoteCreator;
use Spryker\Glue\CartsRestApi\Processor\Quote\SingleQuoteCreatorInterface;
use Spryker\Glue\CartsRestApi\Processor\RestRequest\RestRequestUpdater;
use Spryker\Glue\CartsRestApi\Processor\RestRequest\RestRequestUpdaterInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilder;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @method \Spryker\Glue\CartsRestApi\CartsRestApiConfig getConfig()
 */
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
            $this->getQuoteCollectionReaderPlugin()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Cart\CartCreatorInterface
     */
    public function createCartCreator(): CartCreatorInterface
    {
        return new CartCreator(
            $this->createCartsResourceMapper(),
            $this->getQuoteCreatorPlugin(),
            $this->getResourceBuilder()
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
     * @return \Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartReaderInterface
     */
    public function createGuestCartReader(): GuestCartReaderInterface
    {
        return new GuestCartReader(
            $this->getResourceBuilder(),
            $this->createCartsResourceMapper(),
            $this->getQuoteCollectionReaderPlugin(),
            $this->createGuestCartRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartCreatorInterface
     */
    public function createGuestCartCreator(): GuestCartCreatorInterface
    {
        return new GuestCartCreator(
            $this->getResourceBuilder(),
            $this->createCartsResourceMapper(),
            $this->getPersistentCartClient()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartUpdaterInterface
     */
    public function createGuestCartUpdater(): GuestCartUpdaterInterface
    {
        return new GuestCartUpdater(
            $this->getQuoteClient(),
            $this->getPersistentCartClient()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\GuestCartItem\GuestCartItemAdderInterface
     */
    public function createGuestCartItemAdder(): GuestCartItemAdderInterface
    {
        return new GuestCartItemAdder(
            $this->getCartClient(),
            $this->getQuoteClient(),
            $this->getZedRequestClient(),
            $this->createGuestCartReader(),
            $this->createGuestCartCreator(),
            $this->createCartItemsResourceMapper(),
            $this->createGuestCartRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\GuestCartItem\GuestCartItemUpdaterInterface
     */
    public function createGuestCartItemUpdater(): GuestCartItemUpdaterInterface
    {
        return new GuestCartItemUpdater(
            $this->getCartClient(),
            $this->getResourceBuilder(),
            $this->getZedRequestClient(),
            $this->getQuoteClient(),
            $this->createGuestCartReader()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\GuestCartItem\GuestCartItemDeleterInterface
     */
    public function createGuestCartItemDeleter(): GuestCartItemDeleterInterface
    {
        return new GuestCartItemDeleter(
            $this->getCartClient(),
            $this->getResourceBuilder(),
            $this->getQuoteClient(),
            $this->createGuestCartReader()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface
     */
    public function createGuestCartRestResponseBuilder(): GuestCartRestResponseBuilderInterface
    {
        return new GuestCartRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createCartsResourceMapper(),
            $this->createCartItemsResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\RestRequest\RestRequestUpdaterInterface
     */
    public function createRestRequestUpdater(): RestRequestUpdaterInterface
    {
        return new RestRequestUpdater($this->getPersistentCartClient());
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Quote\QuoteCollectionReaderInterface
     */
    public function createQuoteCollectionReader(): QuoteCollectionReaderInterface
    {
        return new QuoteCollectionReader($this->getCartClient());
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Quote\SingleQuoteCreatorInterface
     */
    public function createSingleQuoteCreator(): SingleQuoteCreatorInterface
    {
        return new SingleQuoteCreator(
            $this->createCartReader(),
            $this->getPersistentCartClient()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\GuestCart\AnonymousCustomerUniqueIdValidatorInterface
     */
    public function createAnonymousCustomerUniqueIdValidator(): AnonymousCustomerUniqueIdValidatorInterface
    {
        return new AnonymousCustomerUniqueIdValidator($this->getConfig());
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
     * @return \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface
     */
    public function getQuoteCreatorPlugin(): QuoteCreatorPluginInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::PLUGIN_QUOTE_CREATOR);
    }
}
