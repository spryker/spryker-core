<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilder;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilderInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\DataProvider\ProductTableDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\DataProvider\ProductTableDataProviderInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\HasOffersProductTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\IsActiveProductTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\ProductTableFilterDataProviderInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\ProductTable;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilDateTimeServiceInterface;
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
            $this->createProductTableFilterDataProviders(),
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
            $this->getTranslatorFacade(),
            $this->getUtilDateTimeService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\ProductTableFilterDataProviderInterface
     */
    public function createIsActiveProductTableFilterDataProvider(): ProductTableFilterDataProviderInterface
    {
        return new IsActiveProductTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\ProductTableFilterDataProviderInterface
     */
    public function createHasOffersProductTableFilterDataProvider(): ProductTableFilterDataProviderInterface
    {
        return new HasOffersProductTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\ProductTableFilterDataProviderInterface[]
     */
    public function createProductTableFilterDataProviders(): array
    {
        return [
            $this->createIsActiveProductTableFilterDataProvider(),
            $this->createHasOffersProductTableFilterDataProvider(),
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
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): ProductOfferGuiPageToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::SERVICE_UTIL_DATE_TIME);
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
}
