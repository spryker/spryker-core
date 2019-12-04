<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferAvailability\Business\Availability\ProductOfferAvailabilityProvider;
use Spryker\Zed\ProductOfferAvailability\Business\Availability\ProductOfferAvailabilityProviderInterface;
use Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToOmsFacadeInterface;
use Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferStockFacadeInterface;
use Spryker\Zed\ProductOfferAvailability\ProductOfferAvailabilityDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferAvailability\ProductOfferAvailabilityConfig getConfig()
 */
class ProductOfferAvailabilityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToOmsFacadeInterface
     */
    public function getOmsFacade(): ProductOfferAvailabilityToOmsFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferStockFacadeInterface
     */
    public function getProductOfferStockFacade(): ProductOfferAvailabilityToProductOfferStockFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityDependencyProvider::FACADE_PRODUCT_OFFER_STOCK);
    }

    /**
     * @return \Spryker\Zed\ProductOfferAvailability\Business\Availability\ProductOfferAvailabilityProviderInterface
     */
    public function createProductOfferAvailabilityProvider(): ProductOfferAvailabilityProviderInterface
    {
        return new ProductOfferAvailabilityProvider(
            $this->getOmsFacade(),
            $this->getProductOfferStockFacade()
        );
    }
}
