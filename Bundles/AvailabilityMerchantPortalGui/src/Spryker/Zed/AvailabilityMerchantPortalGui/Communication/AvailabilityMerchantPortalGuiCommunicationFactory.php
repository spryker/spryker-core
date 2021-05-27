<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityMerchantPortalGui\Communication;

use Spryker\Zed\AvailabilityMerchantPortalGui\AvailabilityMerchantPortalGuiDependencyProvider;
use Spryker\Zed\AvailabilityMerchantPortalGui\Communication\Expander\ProductConcreteTableExpander;
use Spryker\Zed\AvailabilityMerchantPortalGui\Communication\Expander\ProductConcreteTableExpanderInterface;
use Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToAvailabilityFacadeInterface;
use Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToMerchantStockFacadeInterface;
use Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class AvailabilityMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\AvailabilityMerchantPortalGui\Communication\Expander\ProductConcreteTableExpanderInterface
     */
    public function createProductConcreteTableExpander(): ProductConcreteTableExpanderInterface
    {
        return new ProductConcreteTableExpander(
            $this->getAvailabilityFacade(),
            $this->getMerchantStockFacade(),
            $this->getMerchantUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToAvailabilityFacadeInterface
     */
    public function getAvailabilityFacade(): AvailabilityMerchantPortalGuiToAvailabilityFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityMerchantPortalGuiDependencyProvider::FACADE_AVAILABILITY);
    }

    /**
     * @return \Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): AvailabilityMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToMerchantStockFacadeInterface
     */
    public function getMerchantStockFacade(): AvailabilityMerchantPortalGuiToMerchantStockFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_STOCK);
    }
}
