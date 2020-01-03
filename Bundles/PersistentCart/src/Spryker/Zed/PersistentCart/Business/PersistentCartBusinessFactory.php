<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PersistentCart\Business\Locker\QuoteLocker;
use Spryker\Zed\PersistentCart\Business\Locker\QuoteLockerInterface;
use Spryker\Zed\PersistentCart\Business\Model\CartChangeRequestExpander;
use Spryker\Zed\PersistentCart\Business\Model\CartChangeRequestExpanderInterface;
use Spryker\Zed\PersistentCart\Business\Model\CartOperation;
use Spryker\Zed\PersistentCart\Business\Model\CartOperationInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteDeleter;
use Spryker\Zed\PersistentCart\Business\Model\QuoteDeleterInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperation;
use Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperationInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteMerger;
use Spryker\Zed\PersistentCart\Business\Model\QuoteMergerInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResolver;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpander;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteStorageSynchronizer;
use Spryker\Zed\PersistentCart\Business\Model\QuoteStorageSynchronizerInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteWriter;
use Spryker\Zed\PersistentCart\Business\Model\QuoteWriterInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToStoreFacadeInterface;
use Spryker\Zed\PersistentCart\PersistentCartDependencyProvider;
use Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;

/**
 * @method \Spryker\Zed\PersistentCart\PersistentCartConfig getConfig()
 */
class PersistentCartBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\CartOperationInterface
     */
    public function createCartOperation(): CartOperationInterface
    {
        return new CartOperation(
            $this->getQuoteItemFinderPlugin(),
            $this->createQuoteResponseExpander(),
            $this->createQuoteResolver(),
            $this->createQuoteItemOperation(),
            $this->getQuoteFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperationInterface
     */
    public function createQuoteItemOperation(): QuoteItemOperationInterface
    {
        return new QuoteItemOperation(
            $this->getCartFacade(),
            $this->getQuoteFacade(),
            $this->createCartChangeRequestExpander(),
            $this->createQuoteResponseExpander(),
            $this->getMessengerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface
     */
    public function createQuoteResolver(): QuoteResolverInterface
    {
        return new QuoteResolver(
            $this->getQuoteFacade(),
            $this->createQuoteResponseExpander(),
            $this->getMessengerFacade(),
            $this->getStoreFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\QuoteStorageSynchronizerInterface
     */
    public function createQuoteStorageSynchronizer(): QuoteStorageSynchronizerInterface
    {
        return new QuoteStorageSynchronizer(
            $this->getCartFacade(),
            $this->getQuoteFacade(),
            $this->createQuoteResponseExpander(),
            $this->createQuoteMerger(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\QuoteDeleterInterface
     */
    public function createQuoteDeleter(): QuoteDeleterInterface
    {
        return new QuoteDeleter(
            $this->getQuoteFacade(),
            $this->createQuoteResponseExpander(),
            $this->getMessengerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\QuoteWriterInterface
     */
    public function createQuoteWriter(): QuoteWriterInterface
    {
        return new QuoteWriter(
            $this->getQuoteFacade(),
            $this->createQuoteResponseExpander(),
            $this->createQuoteResolver(),
            $this->createQuoteItemOperation()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface
     */
    public function createQuoteResponseExpander(): QuoteResponseExpanderInterface
    {
        return new QuoteResponseExpander(
            $this->getQuoteResponseExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\CartChangeRequestExpanderInterface
     */
    public function createCartChangeRequestExpander(): CartChangeRequestExpanderInterface
    {
        return new CartChangeRequestExpander(
            $this->getRemoveItemsRequestExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\QuoteMergerInterface
     */
    public function createQuoteMerger(): QuoteMergerInterface
    {
        return new QuoteMerger(
            $this->getCartAddItemStrategyPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Business\Locker\QuoteLockerInterface
     */
    public function createQuoteLocker(): QuoteLockerInterface
    {
        return new QuoteLocker(
            $this->getCartFacade(),
            $this->createQuoteResolver(),
            $this->getQuoteFacade(),
            $this->createQuoteResponseExpander()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface
     */
    public function getCartFacade()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::FACADE_CART);
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeInterface
     */
    public function getMessengerFacade()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface
     */
    public function getQuoteFacade()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToStoreFacadeInterface
     */
    public function getStoreFacade(): PersistentCartToStoreFacadeInterface
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface
     */
    protected function getQuoteItemFinderPlugin(): QuoteItemFinderPluginInterface
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGIN_QUOTE_ITEM_FINDER);
    }

    /**
     * @return \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\CartChangeRequestExpandPluginInterface[]
     */
    protected function getRemoveItemsRequestExpanderPlugins(): array
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_REMOVE_ITEMS_REQUEST_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteResponseExpanderPluginInterface[]
     */
    protected function getQuoteResponseExpanderPlugins(): array
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_QUOTE_RESPONSE_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationStrategyPluginInterface[]
     */
    public function getCartAddItemStrategyPlugins(): array
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_CART_ADD_ITEM_STRATEGY);
    }
}
