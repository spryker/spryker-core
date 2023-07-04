<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface;
use Spryker\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferServicePointStorage\Persistence\ProductOfferServicePointStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOfferServicePointStorage\Persistence\ProductOfferServicePointStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferServicePointStorage\Business\ProductOfferServicePointStorageFacadeInterface getFacade()
 */
class ProductOfferServicePointStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface
     */
    public function getProductOfferServicePointFacade(): ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointStorageDependencyProvider::FACADE_PRODUCT_OFFER_SERVICE_POINT);
    }
}
