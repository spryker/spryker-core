<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferGuiPage\Business\MerchantUserResolver\MerchantUserResolver;
use Spryker\Zed\ProductOfferGuiPage\Business\MerchantUserResolver\MerchantUserResolverInterface;
use Spryker\Zed\ProductOfferGuiPage\Business\ProductListTableDataProvider\ProductListTableDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Business\ProductListTableDataProvider\ProductListTableDataProviderInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToUserFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\ProductOfferGuiPageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferGuiPage\ProductOfferGuiPageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface getRepository()
 */
class ProductOfferGuiPageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Business\ProductListTableDataProvider\ProductListTableDataProviderInterface
     */
    public function createProductListTableDataProvider(): ProductListTableDataProviderInterface
    {
        return new ProductListTableDataProvider(
            $this->getProductImageFacade(),
            $this->getLocaleFacade(),
            $this->createMerchantUserResolver(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Business\MerchantUserResolver\MerchantUserResolverInterface
     */
    public function createMerchantUserResolver(): MerchantUserResolverInterface
    {
        return new MerchantUserResolver(
            $this->getUserFacade(),
            $this->getMerchantUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface
     */
    public function getProductImageFacade(): ProductOfferGuiPageToProductImageFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToUserFacadeInterface
     */
    public function getUserFacade(): ProductOfferGuiPageToUserFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): ProductOfferGuiPageToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductOfferGuiPageToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::FACADE_LOCALE);
    }
}
