<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferStorage\ProductOfferStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOfferStorage\ProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferStorage\Business\ProductOfferStorageFacadeInterface getFacade()
 */
class ProductOfferStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferStorage\Dependency\Facade\ProductOfferStorageToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferStorageToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferStorageDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
