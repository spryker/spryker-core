<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferMerchantPortalGui\CocreateRequestToGuiTableDataRequestHydratormmunication\Table\ProductOfferTable\Filter\UpdateProductOfferTableFilterDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilder;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\AbstractTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\DateRangeFilterValueNormalizerPlugin;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\RequestToGuiTableDataRequestHydrator;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\RequestToGuiTableDataRequestHydratorInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaBuilder\ProductOfferTableCriteriaBuilder;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaBuilder\ProductOfferTableCriteriaBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\CreationFilterProductOfferTableCriteriaExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\IsVisibleFilterProductOfferTableCriteriaExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\StockFilterProductOfferTableCriteriaExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\StoresFilterProductOfferTableCriteriaExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\UpdateFilterProductOfferTableCriteriaExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\ValidityFilterProductOfferTableCriteriaExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\DataProvider\ProductOfferTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\DataProvider\ProductOfferTableDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter\CreationProductOfferTableFilterDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter\IsVisibleProductOfferTableFilterDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter\StockProductOfferTableFilterDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter\StoresProductOfferTableFilterDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter\ValidityProductOfferTableFilterDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductOfferTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\TableDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class ProductOfferMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable
     */
    public function createProductTable(): ProductTable
    {
        return new ProductTable(
            $this->getTranslatorFacade(),
            $this->createProductTableDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\TableDataProviderInterface
     */
    public function createProductTableDataProvider(): TableDataProviderInterface
    {
        return new ProductTableDataProvider(
            $this->getRepository(),
            $this->getTranslatorFacade(),
            $this->getUtilDateTimeService(),
            $this->createProductNameBuilder(),
            $this->getMerchantUserFacade(),
            $this->getLocaleFacade(),
            $this->createRequestToGuiTableDataRequestHydrator()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductOfferTable
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
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaBuilder\ProductOfferTableCriteriaBuilderInterface
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
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\DataProvider\ProductOfferTableDataProviderInterface
     */
    public function createProductOfferTableDataProvider(): ProductOfferTableDataProviderInterface
    {
        return new ProductOfferTableDataProvider(
            $this->getRepository(),
            $this->getTranslatorFacade(),
            $this->getUtilDateTimeService(),
            $this->createProductNameBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface
     */
    public function createIsVisibleProductOfferTableFilterDataProvider(): TableFilterDataProviderInterface
    {
        return new IsVisibleProductOfferTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface
     */
    public function createStockProductOfferTableFilterDataProvider(): TableFilterDataProviderInterface
    {
        return new StockProductOfferTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface
     */
    public function createStoresProductOfferTableFilterDataProvider(): TableFilterDataProviderInterface
    {
        return new StoresProductOfferTableFilterDataProvider($this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface
     */
    public function createValidityProductOfferTableFilterDataProvider(): TableFilterDataProviderInterface
    {
        return new ValidityProductOfferTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface
     */
    public function createCreationProductOfferTableFilterDataProvider(): TableFilterDataProviderInterface
    {
        return new CreationProductOfferTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface
     */
    public function createUpdateProductOfferTableFilterDataProvider(): TableFilterDataProviderInterface
    {
        return new UpdateProductOfferTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface[]
     */
    public function getProductOfferTableFilterDataProviders(): array
    {
        return [
            $this->createIsVisibleProductOfferTableFilterDataProvider(),
            $this->createStockProductOfferTableFilterDataProvider(),
            $this->createStoresProductOfferTableFilterDataProvider(),
            $this->createValidityProductOfferTableFilterDataProvider(),
            $this->createCreationProductOfferTableFilterDataProvider(),
            $this->createUpdateProductOfferTableFilterDataProvider(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface
     */
    public function createIsVisibleFilterProductOfferTableCriteriaExpander(): FilterProductOfferTableCriteriaExpanderInterface
    {
        return new IsVisibleFilterProductOfferTableCriteriaExpander(
            $this->createIsVisibleProductOfferTableFilterDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface
     */
    public function createStockFilterProductOfferTableCriteriaExpander(): FilterProductOfferTableCriteriaExpanderInterface
    {
        return new StockFilterProductOfferTableCriteriaExpander(
            $this->createStockProductOfferTableFilterDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface
     */
    public function createStoresFilterProductOfferTableCriteriaExpander(): FilterProductOfferTableCriteriaExpanderInterface
    {
        return new StoresFilterProductOfferTableCriteriaExpander(
            $this->createStoresProductOfferTableFilterDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface
     */
    public function createValidityFilterProductOfferTableCriteriaExpander(): FilterProductOfferTableCriteriaExpanderInterface
    {
        return new ValidityFilterProductOfferTableCriteriaExpander(
            $this->createValidityProductOfferTableFilterDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface
     */
    public function createCreationFilterProductOfferTableCriteriaExpander(): FilterProductOfferTableCriteriaExpanderInterface
    {
        return new CreationFilterProductOfferTableCriteriaExpander(
            $this->createCreationProductOfferTableFilterDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface
     */
    public function createUpdateFilterProductOfferTableCriteriaExpander(): FilterProductOfferTableCriteriaExpanderInterface
    {
        return new UpdateFilterProductOfferTableCriteriaExpander(
            $this->createUpdateProductOfferTableFilterDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface[]
     */
    public function getFilterProductOfferTableCriteriaExpanders(): array
    {
        return [
            $this->createIsVisibleFilterProductOfferTableCriteriaExpander(),
            $this->createStockFilterProductOfferTableCriteriaExpander(),
            $this->createStoresFilterProductOfferTableCriteriaExpander(),
            $this->createValidityFilterProductOfferTableCriteriaExpander(),
            $this->createCreationFilterProductOfferTableCriteriaExpander(),
            $this->createUpdateFilterProductOfferTableCriteriaExpander(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface
     */
    public function createProductNameBuilder(): ProductNameBuilderInterface
    {
        return new ProductNameBuilder();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductOfferMerchantPortalGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductOfferMerchantPortalGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\RequestToGuiTableDataRequestHydratorInterface
     */
    public function createRequestToGuiTableDataRequestHydrator(): RequestToGuiTableDataRequestHydratorInterface
    {
        return new RequestToGuiTableDataRequestHydrator(
            $this->getUtilEncodingService(),
            // @todo refactor to plugin stack
            [
                $this->createDateRangeFilterValueNormalizerPlugin()
            ]
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\DateRangeFilterValueNormalizerPlugin
     */
    public function createDateRangeFilterValueNormalizerPlugin(): DateRangeFilterValueNormalizerPlugin
    {
        return new DateRangeFilterValueNormalizerPlugin();
    }
}
