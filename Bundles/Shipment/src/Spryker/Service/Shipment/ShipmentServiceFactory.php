<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Shipment\Dependency\Service\ShipmentToCustomerServiceInterface;
use Spryker\Service\Shipment\Items\ItemsGrouper;
use Spryker\Service\Shipment\Items\ItemsGrouperInterface;
use Spryker\Service\Shipment\ShipmentHash\ShipmentHashGenerator;
use Spryker\Service\Shipment\ShipmentHash\ShipmentHashGeneratorInterface;

/**
 * @method \Spryker\Service\Shipment\ShipmentConfig getConfig()
 */
class ShipmentServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Shipment\Items\ItemsGrouperInterface
     */
    public function createItemsGrouper(): ItemsGrouperInterface
    {
        return new ItemsGrouper($this->createShipmentHashGenerator());
    }

    /**
     * @return \Spryker\Service\Shipment\ShipmentHash\ShipmentHashGeneratorInterface
     */
    public function createShipmentHashGenerator(): ShipmentHashGeneratorInterface
    {
        return new ShipmentHashGenerator($this->getCustomerService());
    }

    /**
     * @return \Spryker\Service\Shipment\Dependency\Service\ShipmentToCustomerServiceInterface
     */
    public function getCustomerService(): ShipmentToCustomerServiceInterface
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::SERVICE_CUSTOMER);
    }
}
