<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Business\ProductOfferAvailabilityStorageFacadeInterface getFacade()
 */
class ProductOfferAvailabilityStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade\ProductOfferAvailabilityStorageToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferAvailabilityStorageToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
