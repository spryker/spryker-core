<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi;

use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCustomerClientInterface;
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
use Spryker\Glue\CartsRestApi\Processor\Expander\CartItemByQuoteResourceRelationshipExpander;
use Spryker\Glue\CartsRestApi\Processor\Expander\CartItemByQuoteResourceRelationshipExpanderInterface;
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
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemMapper;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartMapper;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartMapperInterface;
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
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\ItemResponseBuilder;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\ItemResponseBuilderInterface;
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
            $this->getClient(),
            $this->getCustomerExpanderPlugins(),
            $this->getCustomerClient()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Cart\CartCreatorInterface
     */
    public function createCartCreator(): CartCreatorInterface
    {
        return new CartCreator(
            $this->createCartMapper(),
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
            $this->createCartMapper(),
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
            $this->createCartMapper(),
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
            $this->getCustomerExpanderPlugins(),
            $this->getCartItemExpanderPlugins()
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
            $this->createGuestCartRestResponseBuilder(),
            $this->getClient(),
            $this->createCartMapper(),
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
            $this->createGuestCartRestResponseBuilder(),
            $this->createCartRestResponseBuilder(),
            $this->getCartItemExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\GuestCartItem\GuestCartItemUpdaterInterface
     */
    public function createGuestCartItemUpdater(): GuestCartItemUpdaterInterface
    {
        return new GuestCartItemUpdater(
            $this->getClient(),
            $this->createGuestCartRestResponseBuilder(),
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
            $this->createCartMapper(),
            $this->createItemResponseBuilder(),
            $this->getConfig(),
            $this->getCartItemFilterPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface
     */
    public function createGuestCartRestResponseBuilder(): GuestCartRestResponseBuilderInterface
    {
        return new GuestCartRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createCartMapper(),
            $this->createItemResponseBuilder(),
            $this->getConfig(),
            $this->getCartItemFilterPlugins()
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
     * @return \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemMapperInterface
     */
    public function createCartItemMapper(): CartItemMapperInterface
    {
        return new CartItemMapper($this->getRestCartItemsAttributesMapperPlugins());
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Mapper\CartMapperInterface
     */
    public function createCartMapper(): CartMapperInterface
    {
        return new CartMapper(
            $this->getResourceBuilder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\Expander\CartItemByQuoteResourceRelationshipExpanderInterface
     */
    public function createCartItemByQuoteResourceRelationshipExpander(): CartItemByQuoteResourceRelationshipExpanderInterface
    {
        return new CartItemByQuoteResourceRelationshipExpander(
            $this->createCartReader(),
            $this->getCartItemFilterPlugins(),
            $this->createItemResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\ItemResponseBuilderInterface
     */
    public function createItemResponseBuilder(): ItemResponseBuilderInterface
    {
        return new ItemResponseBuilder(
            $this->getResourceBuilder(),
            $this->createCartItemMapper()
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

    /**
     * @return \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\RestCartItemsAttributesMapperPluginInterface[]
     */
    public function getRestCartItemsAttributesMapperPlugins(): array
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::PLUGINS_REST_CART_ITEMS_ATTRIBUTES_MAPPER);
    }

    /**
     * @return \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CartItemExpanderPluginInterface[]
     */
    public function getCartItemExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::PLUGINS_CART_ITEM_EXPANDER);
    }

    /**
     * @return \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CartItemFilterPluginInterface[]
     */
    public function getCartItemFilterPlugins(): array
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::PLUGINS_CART_ITEM_FILTER);
    }

    /**
     * @return \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCustomerClientInterface
     */
    public function getCustomerClient(): CartsRestApiToCustomerClientInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::CLIENT_CUSTOMER);
    }
}
