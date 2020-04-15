<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilder;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\DataProvider\ProductTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\DataProvider\ProductTableDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter\HasOffersProductTableFilterDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter\IsActiveProductTableFilterDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter\ProductTableFilterDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
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
            $this->getUtilEncodingService(),
            $this->getTranslatorFacade(),
            $this->createProductTableDataProvider(),
            $this->createProductTableFilterDataProviders(),
            $this->createProductTableCriteriaBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilderInterface
     */
    public function createProductTableCriteriaBuilder(): ProductTableCriteriaBuilderInterface
    {
        return new ProductTableCriteriaBuilder(
            $this->getMerchantUserFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\DataProvider\ProductTableDataProviderInterface
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
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter\ProductTableFilterDataProviderInterface
     */
    public function createIsActiveProductTableFilterDataProvider(): ProductTableFilterDataProviderInterface
    {
        return new IsActiveProductTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter\ProductTableFilterDataProviderInterface
     */
    public function createHasOffersProductTableFilterDataProvider(): ProductTableFilterDataProviderInterface
    {
        return new HasOffersProductTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter\ProductTableFilterDataProviderInterface[]
     */
    public function createProductTableFilterDataProviders(): array
    {
        return [
            $this->createIsActiveProductTableFilterDataProvider(),
            $this->createHasOffersProductTableFilterDataProvider(),
        ];
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
}
