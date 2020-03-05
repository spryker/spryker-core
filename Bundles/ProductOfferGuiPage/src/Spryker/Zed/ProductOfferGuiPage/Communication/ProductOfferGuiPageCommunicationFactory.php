<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\CategoryProductListTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\IsActiveProductListTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\ProductListTableFilterDataProviderInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\StoresProductListTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\ProductListTable;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToCategoryFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToStoreFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface;
use Spryker\Zed\ProductOfferGuiPage\ProductOfferGuiPageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferGuiPage\ProductOfferGuiPageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferGuiPage\Business\ProductOfferGuiPageFacadeInterface getFacade()
 */
class ProductOfferGuiPageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\ProductListTable
     */
    public function createProductListTable(): ProductListTable
    {
        return new ProductListTable(
            $this->getFacade(),
            $this->createProductListTableFilterDataProviders()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\ProductListTableFilterDataProviderInterface
     */
    public function createCategoryProductListTableFilterDataProvider(): ProductListTableFilterDataProviderInterface
    {
        return new CategoryProductListTableFilterDataProvider(
            $this->getCategoryFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\ProductListTableFilterDataProviderInterface
     */
    public function createIsActiveProductListTableFilterDataProvider(): ProductListTableFilterDataProviderInterface
    {
        return new IsActiveProductListTableFilterDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\ProductListTableFilterDataProviderInterface
     */
    public function createStoresProductListTableFilterDataProvider(): ProductListTableFilterDataProviderInterface
    {
        return new StoresProductListTableFilterDataProvider($this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\ProductListTableFilterDataProviderInterface[]
     */
    public function createProductListTableFilterDataProviders(): array
    {
        return [
            $this->createCategoryProductListTableFilterDataProvider(),
            $this->createIsActiveProductListTableFilterDataProvider(),
            $this->createStoresProductListTableFilterDataProvider(),
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
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToCategoryFacadeInterface
     */
    public function getCategoryFacade(): ProductOfferGuiPageToCategoryFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductOfferGuiPageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::FACADE_STORE);
    }
}
