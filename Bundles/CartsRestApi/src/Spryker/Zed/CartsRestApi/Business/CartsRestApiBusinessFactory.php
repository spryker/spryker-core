<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business;

use Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionChecker;
use Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapper;
use Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreator;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteDeleter;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteDeleterInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteErrorIdentifierAdder;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteErrorIdentifierAdderInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReader;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteUpdater;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteUpdaterInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteUuidWriter;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteUuidWriterInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\SingleQuoteCreator;
use Spryker\Zed\CartsRestApi\Business\Quote\SingleQuoteCreatorInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\GuestQuoteItemAdder;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\GuestQuoteItemAdderInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapper;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemAdder;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemAdderInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemDeleter;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemDeleterInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemReader;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemReaderInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemUpdater;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemUpdaterInterface;
use Spryker\Zed\CartsRestApi\CartsRestApiDependencyProvider;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CartsRestApi\CartsRestApiConfig getConfig()
 */
class CartsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\QuoteUuidWriterInterface
     */
    public function createQuoteUuidWriter(): QuoteUuidWriterInterface
    {
        return new QuoteUuidWriter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    public function createQuoteReader(): QuoteReaderInterface
    {
        return new QuoteReader(
            $this->getQuoteFacade(),
            $this->getStoreFacade(),
            $this->createQuotePermissionChecker(),
            $this->getQuoteCollectionExpanderPlugins(),
            $this->getQuoteExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface
     */
    public function createQuoteCreator(): QuoteCreatorInterface
    {
        return new QuoteCreator(
            $this->getQuoteCreatorPlugin(),
            $this->getStoreFacade(),
            $this->createQuoteErrorIdentifierAdder()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\SingleQuoteCreatorInterface
     */
    public function createSingleQuoteCreator(): SingleQuoteCreatorInterface
    {
        return new SingleQuoteCreator(
            $this->getPersistentCartFacade(),
            $this->createQuoteReader()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\QuoteDeleterInterface
     */
    public function createQuoteDeleter(): QuoteDeleterInterface
    {
        return new QuoteDeleter(
            $this->getPersistentCartFacade(),
            $this->createQuoteReader(),
            $this->createQuotePermissionChecker()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\QuoteUpdaterInterface
     */
    public function createQuoteUpdater(): QuoteUpdaterInterface
    {
        return new QuoteUpdater(
            $this->getPersistentCartFacade(),
            $this->getCartFacade(),
            $this->createQuoteReader(),
            $this->createQuoteMapper(),
            $this->createQuotePermissionChecker(),
            $this->createQuoteErrorIdentifierAdder()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemAdderInterface
     */
    public function createQuoteItemAdder(): QuoteItemAdderInterface
    {
        return new QuoteItemAdder(
            $this->getPersistentCartFacade(),
            $this->createQuoteReader(),
            $this->createQuoteItemMapper(),
            $this->createQuotePermissionChecker()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\QuoteItem\GuestQuoteItemAdderInterface
     */
    public function createGuestQuoteItemAdder(): GuestQuoteItemAdderInterface
    {
        return new GuestQuoteItemAdder(
            $this->createQuoteReader(),
            $this->createQuoteItemAdder(),
            $this->createQuoteCreator(),
            $this->getStoreFacade(),
            $this->getQuoteFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemReaderInterface
     */
    public function createQuoteItemReader(): QuoteItemReaderInterface
    {
        return new QuoteItemReader(
            $this->createQuoteReader(),
            $this->createQuoteItemMapper()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemDeleterInterface
     */
    public function createQuoteItemDeleter(): QuoteItemDeleterInterface
    {
        return new QuoteItemDeleter(
            $this->getPersistentCartFacade(),
            $this->createQuoteItemReader(),
            $this->createQuotePermissionChecker()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemUpdaterInterface
     */
    public function createQuoteItemUpdater(): QuoteItemUpdaterInterface
    {
        return new QuoteItemUpdater(
            $this->getPersistentCartFacade(),
            $this->createQuoteItemReader(),
            $this->createQuotePermissionChecker()
        );
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\QuoteErrorIdentifierAdderInterface
     */
    public function createQuoteErrorIdentifierAdder(): QuoteErrorIdentifierAdderInterface
    {
        return new QuoteErrorIdentifierAdder();
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface
     */
    public function createQuotePermissionChecker(): QuotePermissionCheckerInterface
    {
        return new QuotePermissionChecker();
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface
     */
    public function createQuoteItemMapper(): QuoteItemMapperInterface
    {
        return new QuoteItemMapper();
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface
     */
    public function createQuoteMapper(): QuoteMapperInterface
    {
        return new QuoteMapper();
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface
     */
    public function getQuoteFacade(): CartsRestApiToQuoteFacadeInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface
     */
    public function getStoreFacade(): CartsRestApiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface
     */
    public function getPersistentCartFacade(): CartsRestApiToPersistentCartFacadeInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::FACADE_PERSISTENT_CART);
    }

    /**
     * @return \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface
     */
    public function getCartFacade(): CartsRestApiToCartFacadeInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::FACADE_CART);
    }

    /**
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface
     */
    public function getQuoteCreatorPlugin(): QuoteCreatorPluginInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::PLUGIN_QUOTE_CREATOR);
    }

    /**
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionExpanderPluginInterface[]
     */
    protected function getQuoteCollectionExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::PLUGINS_QUOTE_COLLECTION_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteExpanderPluginInterface[]
     */
    protected function getQuoteExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::PLUGINS_QUOTE_EXPANDER);
    }
}
