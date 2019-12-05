<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi;

use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartCreator;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartCreatorInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartDeleter;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartDeleterInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReader;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartUpdater;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartUpdaterInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemAdder;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemAdderInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemDeleter;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemDeleterInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemUpdater;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemUpdaterInterface;
use Spryker\Glue\CartsRestApi\Processor\GuestCart\AnonymousCustomerUniqueIdValidator;
use Spryker\Glue\CartsRestApi\Processor\GuestCart\AnonymousCustomerUniqueIdValidatorInterface;
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
use Spryker\Glue\CartsRestApi\Processor\Mapper\GuestCartsResourceMapper;
use Spryker\Glue\CartsRestApi\Processor\Quote\QuoteCollectionReader;
use Spryker\Glue\CartsRestApi\Processor\Quote\QuoteCollectionReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\Quote\SingleQuoteCreator;
use Spryker\Glue\CartsRestApi\Processor\Quote\SingleQuoteCreatorInterface;
use Spryker\Glue\CartsRestApi\Processor\RestRequest\RestRequestUpdater;
use Spryker\Glue\CartsRestApi\Processor\RestRequest\RestRequestUpdaterInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilder;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilder;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @method \Spryker\Client\CartsRestApi\CartsRestApiClientInterface getClient()
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
            $this->createCartRestResponseBuilder(),
            $this->createCartsResourceMapper(),
            $this->getClient(),
            $this->getCustomerExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Cart\CartCreatorInterface
     */
    public function createCartCreator(): CartCreatorInterface
    {
        return new CartCreator(
            $this->createCartsResourceMapper(),
            $this->getClient(),
            $this->createCartRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Cart\CartDeleterInterface
     */
    public function createCartDeleter(): CartDeleterInterface
    {
        return new CartDeleter(
            $this->createCartRestResponseBuilder(),
            $this->getClient(),
            $this->createCartsResourceMapper(),
            $this->getCustomerExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Cart\CartUpdaterInterface
     */
    public function createCartUpdater(): CartUpdaterInterface
    {
        return new CartUpdater(
            $this->getClient(),
            $this->createCartsResourceMapper(),
            $this->createCartRestResponseBuilder(),
            $this->getCustomerExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemAdderInterface
     */
    public function createCartItemAdder(): CartItemAdderInterface
    {
        return new CartItemAdder(
            $this->getClient(),
            $this->createCartRestResponseBuilder(),
            $this->createCartItemsResourceMapper(),
            $this->createCartsResourceMapper(),
            $this->getCustomerExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemDeleterInterface
     */
    public function createCartItemDeleter(): CartItemDeleterInterface
    {
        return new CartItemDeleter(
            $this->getClient(),
            $this->createCartRestResponseBuilder(),
            $this->createCartItemsResourceMapper(),
            $this->getCustomerExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemUpdaterInterface
     */
    public function createCartItemUpdater(): CartItemUpdaterInterface
    {
        return new CartItemUpdater(
            $this->getClient(),
            $this->createCartRestResponseBuilder(),
            $this->createCartsResourceMapper(),
            $this->createCartItemsResourceMapper(),
            $this->getCustomerExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartReaderInterface
     */
    public function createGuestCartReader(): GuestCartReaderInterface
    {
        return new GuestCartReader(
            $this->createGuestCartRestResponseBuilder(),
            $this->createCartReader(),
            $this->getClient()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartUpdaterInterface
     */
    public function createGuestCartUpdater(): GuestCartUpdaterInterface
    {
        return new GuestCartUpdater(
            $this->createCartUpdater(),
            $this->createGuestCartRestResponseBuilder(),
            $this->getClient(),
            $this->createCartsResourceMapper(),
            $this->getCustomerExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\GuestCartItem\GuestCartItemAdderInterface
     */
    public function createGuestCartItemAdder(): GuestCartItemAdderInterface
    {
        return new GuestCartItemAdder(
            $this->getClient(),
            $this->createCartItemsResourceMapper(),
            $this->createGuestCartRestResponseBuilder(),
            $this->createCartRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\GuestCartItem\GuestCartItemUpdaterInterface
     */
    public function createGuestCartItemUpdater(): GuestCartItemUpdaterInterface
    {
        return new GuestCartItemUpdater(
            $this->getClient(),
            $this->createCartRestResponseBuilder(),
            $this->createGuestCartsResourceMapper(),
            $this->createCartItemsResourceMapper(),
            $this->getCustomerExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\GuestCartItem\GuestCartItemDeleterInterface
     */
    public function createGuestCartItemDeleter(): GuestCartItemDeleterInterface
    {
        return new GuestCartItemDeleter(
            $this->getClient(),
            $this->createCartRestResponseBuilder(),
            $this->createCartItemsResourceMapper(),
            $this->getCustomerExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface
     */
    public function createCartRestResponseBuilder(): CartRestResponseBuilderInterface
    {
        return new CartRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createCartsResourceMapper()
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
        return new QuoteCollectionReader($this->getClient());
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Quote\SingleQuoteCreatorInterface
     */
    public function createSingleQuoteCreator(): SingleQuoteCreatorInterface
    {
        return new SingleQuoteCreator(
            $this->createCartReader(),
            $this->getClient()
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
        return new CartsResourceMapper(
            $this->createCartItemsResourceMapper(),
            $this->getResourceBuilder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface
     */
    public function createGuestCartsResourceMapper(): CartsResourceMapperInterface
    {
        return new GuestCartsResourceMapper(
            $this->createCartItemsResourceMapper(),
            $this->getResourceBuilder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface
     */
    public function getPersistentCartClient(): CartsRestApiToPersistentCartClientInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::CLIENT_PERSISTENT_CART);
    }

    /**
     * @return \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CustomerExpanderPluginInterface[]
     */
    public function getCustomerExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::PLUGINS_CUSTOMER_EXPANDER);
    }
}
