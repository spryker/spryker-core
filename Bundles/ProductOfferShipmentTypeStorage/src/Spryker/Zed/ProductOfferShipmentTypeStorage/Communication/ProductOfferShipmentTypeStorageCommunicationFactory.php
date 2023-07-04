<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToProductOfferShipmentTypeFacadeInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\ProductOfferShipmentTypeStorageFacadeInterface getFacade()
 */
class ProductOfferShipmentTypeStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToProductOfferShipmentTypeFacadeInterface
     */
    public function getProductOfferShipmentTypeFacade(): ProductOfferShipmentTypeStorageToProductOfferShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferShipmentTypeStorageDependencyProvider::FACADE_PRODUCT_OFFER_SHIPMENT_TYPE);
    }
}
