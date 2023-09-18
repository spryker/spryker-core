<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferAvailability\Business\Availability\ProductOfferAvailabilityProvider;
use Spryker\Zed\ProductOfferAvailability\Business\Availability\ProductOfferAvailabilityProviderInterface;
use Spryker\Zed\ProductOfferAvailability\Business\Expander\ItemExpander;
use Spryker\Zed\ProductOfferAvailability\Business\Expander\ItemExpanderInterface;
use Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToOmsFacadeInterface;
use Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferFacadeInterface;
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
     * @return \Spryker\Zed\ProductOfferAvailability\Dependency\Facade\ProductOfferAvailabilityToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferAvailabilityToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityDependencyProvider::FACADE_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferAvailability\Business\Availability\ProductOfferAvailabilityProviderInterface
     */
    public function createProductOfferAvailabilityProvider(): ProductOfferAvailabilityProviderInterface
    {
        return new ProductOfferAvailabilityProvider(
            $this->getOmsFacade(),
            $this->getProductOfferStockFacade(),
            $this->getProductOfferFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferAvailability\Business\Expander\ItemExpanderInterface
     */
    public function createItemExpander(): ItemExpanderInterface
    {
        return new ItemExpander(
            $this->getOmsFacade(),
            $this->getProductOfferStockFacade(),
        );
    }
}
