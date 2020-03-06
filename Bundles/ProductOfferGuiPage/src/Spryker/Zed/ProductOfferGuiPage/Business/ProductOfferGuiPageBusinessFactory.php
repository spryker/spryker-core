<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferGuiPage\Business\ProductListTableDataProvider\ProductListTableDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Business\ProductListTableDataProvider\ProductListTableDataProviderInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface;
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
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface
     */
    public function getProductImageFacade(): ProductOfferGuiPageToProductImageFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::FACADE_PRODUCT_IMAGE);
    }
}
