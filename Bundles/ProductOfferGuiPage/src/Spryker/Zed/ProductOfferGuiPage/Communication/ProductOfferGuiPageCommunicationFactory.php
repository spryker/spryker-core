<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\AbstractTable;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaBuilder\ProductOfferTableCriteriaBuilder;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaBuilder\ProductOfferTableCriteriaBuilderInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaExpander\IsVisibleFilterProductOfferTableCriteriaExpander;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaExpander\StatusFilterProductOfferTableCriteriaExpander;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaExpander\StockFilterProductOfferTableCriteriaExpander;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaExpander\StoresFilterProductOfferTableCriteriaExpander;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\DataProvider\ProductOfferTableDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\DataProvider\ProductOfferTableDataProviderInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\Filter\IsVisibleProductOfferTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\Filter\StatusProductOfferTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\Filter\StockProductOfferTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\Filter\StoresProductOfferTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\ProductOfferTable;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilder;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilderInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\DataProvider\ProductTableDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\DataProvider\ProductTableDataProviderInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\HasOffersProductTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\IsActiveProductTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\ProductTable;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToStoreFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface;
use Spryker\Zed\ProductOfferGuiPage\ProductOfferGuiPageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface getRepository()
 */
class ProductOfferGuiPageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\ProductTable
     */
    public function createProductTable(): ProductTable
    {
        return new ProductTable(
            $this->getUtilEncodingService(),
            $this->getTranslatorFacade(),
            $this->createProductTableDataProvider(),
            $this->getProductTableFilterDataProviders(),
            $this->createProductTableCriteriaBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilderInterface
     */
    public function createProductTableCriteriaBuilder(): ProductTableCriteriaBuilderInterface
    {
        return new ProductTableCriteriaBuilder(
            $this->getMerchantUserFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\DataProvider\ProductTableDataProviderInterface
     */
    public function createProductTableDataProvider(): ProductTableDataProviderInterface
    {
        return new ProductTableDataProvider(
            $this->getRepository(),
            $this->getTranslatorFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\ProductOfferTable
     */
    public function createProductOfferTable(): AbstractTable
    {
        return new ProductOfferTable(
            $this->getUtilEncodingService(),
            $this->getTranslatorFacade(),
            $this->createProductOfferTableDataProvider(),
            $this->getProductOfferTableFilterDataProviders(),
            $this->createProductOfferTableCriteriaBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaBuilder\ProductOfferTableCriteriaBuilderInterface
     */
    public function createProductOfferTableCriteriaBuilder(): ProductOfferTableCriteriaBuilderInterface
    {
        return new ProductOfferTableCriteriaBuilder(
            $this->getMerchantUserFacade(),
            $this->getLocaleFacade(),
            $this->getFilterProductOfferTableCriteriaExpanders()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\DataProvider\ProductOfferTableDataProviderInterface
     */
    public function createProductOfferTableDataProvider(): ProductOfferTableDataProviderInterface
    {
        return new ProductOfferTableDataProvider(
            $this->getRepository(),
            $this->getTranslatorFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface
     */
    public function createIsActiveProductTableFilterDataProvider(): TableFilterDataProviderInterface
    {
        return new IsActiveProductTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface
     */
    public function createHasOffersProductTableFilterDataProvider(): TableFilterDataProviderInterface
    {
        return new HasOffersProductTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface[]
     */
    public function getProductTableFilterDataProviders(): array
    {
        return [
            $this->createIsActiveProductTableFilterDataProvider(),
            $this->createHasOffersProductTableFilterDataProvider(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface
     */
    public function createIsVisibleProductOfferTableFilterDataProvider(): TableFilterDataProviderInterface
    {
        return new IsVisibleProductOfferTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface
     */
    public function createStockProductOfferTableFilterDataProvider(): TableFilterDataProviderInterface
    {
        return new StockProductOfferTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface
     */
    public function createStatusProductOfferTableFilterDataProvider(): TableFilterDataProviderInterface
    {
        return new StatusProductOfferTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface
     */
    public function createStoresProductOfferTableFilterDataProvider(): TableFilterDataProviderInterface
    {
        return new StoresProductOfferTableFilterDataProvider($this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\Filter\TableFilterDataProviderInterface[]
     */
    public function getProductOfferTableFilterDataProviders(): array
    {
        return [
            $this->createIsVisibleProductOfferTableFilterDataProvider(),
            $this->createStockProductOfferTableFilterDataProvider(),
            $this->createStatusProductOfferTableFilterDataProvider(),
            $this->createStoresProductOfferTableFilterDataProvider(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface
     */
    public function createIsVisibleFilterProductOfferTableCriteriaExpander(): FilterProductOfferTableCriteriaExpanderInterface
    {
        return new IsVisibleFilterProductOfferTableCriteriaExpander();
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface
     */
    public function createStockFilterProductOfferTableCriteriaExpander(): FilterProductOfferTableCriteriaExpanderInterface
    {
        return new StockFilterProductOfferTableCriteriaExpander();
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface
     */
    public function createStatusFilterProductOfferTableCriteriaExpander(): FilterProductOfferTableCriteriaExpanderInterface
    {
        return new StatusFilterProductOfferTableCriteriaExpander();
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface
     */
    public function createStoresFilterProductOfferTableCriteriaExpander(): FilterProductOfferTableCriteriaExpanderInterface
    {
        return new StoresFilterProductOfferTableCriteriaExpander();
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface[]
     */
    public function getFilterProductOfferTableCriteriaExpanders(): array
    {
        return [
            $this->createIsVisibleFilterProductOfferTableCriteriaExpander(),
            $this->createStockFilterProductOfferTableCriteriaExpander(),
            $this->createStatusFilterProductOfferTableCriteriaExpander(),
            $this->createStoresFilterProductOfferTableCriteriaExpander(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductOfferGuiPageToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductOfferGuiPageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): ProductOfferGuiPageToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): ProductOfferGuiPageToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductOfferGuiPageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::FACADE_STORE);
    }
}
