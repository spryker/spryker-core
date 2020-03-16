<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\Hydrator\ProductTableDataHydratorInterface;
use Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\Hydrator\ProductTableDataImageHydrator;
use Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\Hydrator\ProductTableDataOfferHydrator;
use Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\ProductTableDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\ProductTableDataProviderInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\ProductOfferGuiPageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferGuiPage\ProductOfferGuiPageConfig getConfig()
 */
class ProductOfferGuiPageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\ProductTableDataProviderInterface
     */
    public function createProductTableDataProvider(): ProductTableDataProviderInterface
    {
        return new ProductTableDataProvider(
            $this->getRepository(),
            $this->createProductTableDataHydrators()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\Hydrator\ProductTableDataHydratorInterface[]
     */
    public function createProductTableDataHydrators(): array
    {
        return [
            $this->createProductTableDataImageHydrator(),
            $this->createProductTableDataOfferHydrator(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\Hydrator\ProductTableDataHydratorInterface
     */
    public function createProductTableDataImageHydrator(): ProductTableDataHydratorInterface
    {
        return new ProductTableDataImageHydrator($this->getProductImageFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\Hydrator\ProductTableDataHydratorInterface
     */
    public function createProductTableDataOfferHydrator(): ProductTableDataHydratorInterface
    {
        return new ProductTableDataOfferHydrator($this->getProductOfferFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface
     */
    public function getProductImageFacade(): ProductOfferGuiPageToProductImageFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferGuiPageToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
