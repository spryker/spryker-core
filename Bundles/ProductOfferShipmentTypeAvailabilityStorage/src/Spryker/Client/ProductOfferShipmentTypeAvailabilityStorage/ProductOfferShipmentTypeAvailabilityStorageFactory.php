<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Dependency\Client\ProductOfferShipmentTypeAvailabilityStorageToShipmentTypeStorageClientInterface;
use Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Filter\ProductOfferServicePointAvailabilityFilter;
use Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Filter\ProductOfferServicePointAvailabilityFilterInterface;
use Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Reader\ShipmentTypeStorageReader;
use Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Reader\ShipmentTypeStorageReaderInterface;

class ProductOfferShipmentTypeAvailabilityStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Filter\ProductOfferServicePointAvailabilityFilterInterface
     */
    public function createProductOfferServicePointAvailabilityFilter(): ProductOfferServicePointAvailabilityFilterInterface
    {
        return new ProductOfferServicePointAvailabilityFilter(
            $this->createShipmentTypeStorageReader(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Reader\ShipmentTypeStorageReaderInterface
     */
    public function createShipmentTypeStorageReader(): ShipmentTypeStorageReaderInterface
    {
        return new ShipmentTypeStorageReader(
            $this->getShipmentTypeStorageClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Dependency\Client\ProductOfferShipmentTypeAvailabilityStorageToShipmentTypeStorageClientInterface
     */
    public function getShipmentTypeStorageClient(): ProductOfferShipmentTypeAvailabilityStorageToShipmentTypeStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferShipmentTypeAvailabilityStorageDependencyProvider::CLIENT_SHIPMENT_TYPE_STORAGE);
    }
}
