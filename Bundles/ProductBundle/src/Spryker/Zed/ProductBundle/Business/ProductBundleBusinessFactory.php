<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCartAvailabilityCheck;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCheckoutAvailabilityCheck;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandler;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculation;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartChangeObserver;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartChangeObserverInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartItemGroupKeyExpander;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartPostSaveUpdate;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleImageCartExpander;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundlePreReloadUpdater;
use Spryker\Zed\ProductBundle\Business\ProductBundle\CartNote\QuoteBundleItemsFinder;
use Spryker\Zed\ProductBundle\Business\ProductBundle\CartNote\QuoteBundleItemsFinderInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Checkout\ProductBundleOrderSaver;
use Spryker\Zed\ProductBundle\Business\ProductBundle\PersistentCart\ChangeRequestExpander;
use Spryker\Zed\ProductBundle\Business\ProductBundle\PersistentCart\ChangeRequestExpanderInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundle\PersistentCart\QuoteItemFinder;
use Spryker\Zed\ProductBundle\Business\ProductBundle\PersistentCart\QuoteItemFinderInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReader;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleWriter;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Quote\QuoteItemsGrouper;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Quote\QuoteItemsGrouperInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Sales\ProductBundleIdHydrator;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Sales\ProductBundleSalesOrderSaver;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Sales\ProductBundlesSalesOrderHydrate;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockHandler;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockHandlerInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriter;
use Spryker\Zed\ProductBundle\ProductBundleDependencyProvider;

/**
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface getQueryContainer()
 */
class ProductBundleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleWriterInterface
     */
    public function createProductBundleWriter()
    {
        return new ProductBundleWriter(
            $this->getProductFacade(),
            $this->getQueryContainer(),
            $this->createProductBundleStockWriter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface
     */
    public function createProductBundleReader()
    {
        return new ProductBundleReader(
            $this->getQueryContainer(),
            $this->getAvailabilityQueryContainer(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpanderInterface
     */
    public function createProductBundleCartExpander()
    {
        return new ProductBundleCartExpander(
            $this->getQueryContainer(),
            $this->getPriceProductFacade(),
            $this->getProductFacade(),
            $this->getLocaleFacade(),
            $this->getPriceFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpanderInterface
     */
    public function createProductBundleImageCartExpander()
    {
        return new ProductBundleImageCartExpander($this->getProductImageFacade(), $this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartItemGroupKeyExpander
     */
    public function createProductBundleCartItemGroupKeyExpander()
    {
        return new ProductBundleCartItemGroupKeyExpander();
    }

    /**
     * @deprecated Use createProductBundleOrderSaver instead
     *
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Sales\ProductBundleSalesOrderSaverInterface
     */
    public function createProductBundleSalesOrderSaver()
    {
        return new ProductBundleSalesOrderSaver($this->getSalesQueryContainer(), $this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Checkout\ProductBundleOrderSaverInterface
     */
    public function createProductBundleOrderSaver()
    {
        return new ProductBundleOrderSaver(
            $this->getSalesQueryContainer(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculationInterface
     */
    public function createProductBundlePriceCalculator()
    {
        return new ProductBundlePriceCalculation();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartPostSaveUpdateInterface
     */
    public function createProductBundlePostSaveUpdate()
    {
        return new ProductBundleCartPostSaveUpdate();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCartAvailabilityCheckInterface
     */
    public function createProductBundleCartPreCheck()
    {
        return new ProductBundleCartAvailabilityCheck(
            $this->getAvailabilityFacade(),
            $this->getQueryContainer(),
            $this->getAvailabilityQueryContainer(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCheckoutAvailabilityCheckInterface
     */
    public function createProductBundleCheckoutPreCheck()
    {
        return new ProductBundleCheckoutAvailabilityCheck(
            $this->getAvailabilityFacade(),
            $this->getQueryContainer(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface
     */
    public function createProductBundleAvailabilityHandler()
    {
        return new ProductBundleAvailabilityHandler(
            $this->getAvailabilityQueryContainer(),
            $this->getAvailabilityFacade(),
            $this->getQueryContainer(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockHandlerInterface
     */
    public function createProductBundleStockHandler(): ProductBundleStockHandlerInterface
    {
        return new ProductBundleStockHandler(
            $this->getQueryContainer(),
            $this->createProductBundleStockWriter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriterInterface
     */
    public function createProductBundleStockWriter()
    {
        return new ProductBundleStockWriter(
            $this->getQueryContainer(),
            $this->getStockQueryContainer(),
            $this->createProductBundleAvailabilityHandler(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Sales\ProductBundlesSalesOrderHydrateInterface
     */
    public function createProductBundlesSalesOrderHydrate()
    {
        return new ProductBundlesSalesOrderHydrate(
            $this->getSalesQueryContainer(),
            $this->createProductBundlePriceCalculator()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundlePreReloadUpdaterInterface
     */
    public function createProductBundlePreReloadUpdater()
    {
        return new ProductBundlePreReloadUpdater();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Sales\ProductBundleIdHydratorInterface
     */
    public function createProductBundlesIdHydrator()
    {
        return new ProductBundleIdHydrator($this->getProductQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\PersistentCart\ChangeRequestExpanderInterface
     */
    public function createChangeRequestExpander(): ChangeRequestExpanderInterface
    {
        return new ChangeRequestExpander();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\PersistentCart\QuoteItemFinderInterface
     */
    public function createQuoteItemFinder(): QuoteItemFinderInterface
    {
        return new QuoteItemFinder();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\CartNote\QuoteBundleItemsFinderInterface
     */
    public function createQuoteBundleItemsFinder(): QuoteBundleItemsFinderInterface
    {
        return new QuoteBundleItemsFinder();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartChangeObserverInterface
     */
    public function createProductBundleCartChangeObserver(): ProductBundleCartChangeObserverInterface
    {
        return new ProductBundleCartChangeObserver($this->getMessengerFacade());
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Quote\QuoteItemsGrouperInterface
     */
    public function createQuoteItemsGrouper(): QuoteItemsGrouperInterface
    {
        return new QuoteItemsGrouper();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductImageInterface
     */
    protected function getProductImageFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface
     */
    protected function getPriceProductFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface
     */
    protected function getAvailabilityFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_AVAILABILITY);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface
     */
    protected function getAvailabilityQueryContainer()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::QUERY_CONTAINER_AVAILABILITY);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerInterface
     */
    protected function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToStockQueryContainerInterface
     */
    protected function getStockQueryContainer()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::QUERY_CONTAINER_STOCK);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToProductQueryContainerInterface
     */
    protected function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceInterface
     */
    protected function getPriceFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected function getStoreFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToMessengerFacadeInterface
     */
    protected function getMessengerFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_MESSENGER);
    }
}
