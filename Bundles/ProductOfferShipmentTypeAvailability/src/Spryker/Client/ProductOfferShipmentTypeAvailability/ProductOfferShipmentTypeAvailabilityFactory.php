<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeAvailability;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductOfferShipmentTypeAvailability\Dependency\Client\ProductOfferShipmentTypeAvailabilityToShipmentTypeStorageClientInterface;
use Spryker\Client\ProductOfferShipmentTypeAvailability\Filter\ProductOfferServicePointAvailabilityFilter;
use Spryker\Client\ProductOfferShipmentTypeAvailability\Filter\ProductOfferServicePointAvailabilityFilterInterface;
use Spryker\Client\ProductOfferShipmentTypeAvailability\Reader\ShipmentTypeStorageReader;
use Spryker\Client\ProductOfferShipmentTypeAvailability\Reader\ShipmentTypeStorageReaderInterface;

class ProductOfferShipmentTypeAvailabilityFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductOfferShipmentTypeAvailability\Filter\ProductOfferServicePointAvailabilityFilterInterface
     */
    public function createProductOfferServicePointAvailabilityFilter(): ProductOfferServicePointAvailabilityFilterInterface
    {
        return new ProductOfferServicePointAvailabilityFilter(
            $this->createShipmentTypeStorageReader(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferShipmentTypeAvailability\Reader\ShipmentTypeStorageReaderInterface
     */
    public function createShipmentTypeStorageReader(): ShipmentTypeStorageReaderInterface
    {
        return new ShipmentTypeStorageReader(
            $this->getShipmentTypeStorageClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferShipmentTypeAvailability\Dependency\Client\ProductOfferShipmentTypeAvailabilityToShipmentTypeStorageClientInterface
     */
    public function getShipmentTypeStorageClient(): ProductOfferShipmentTypeAvailabilityToShipmentTypeStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferShipmentTypeAvailabilityDependencyProvider::CLIENT_SHIPMENT_TYPE_STORAGE);
    }
}
