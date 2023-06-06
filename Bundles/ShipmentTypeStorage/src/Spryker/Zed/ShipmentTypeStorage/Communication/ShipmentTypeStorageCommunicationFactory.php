<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentTypeFacadeInterface;
use Spryker\Zed\ShipmentTypeStorage\ShipmentTypeStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentTypeStorage\ShipmentTypeStorageConfig getConfig()
 * @method \Spryker\Zed\ShipmentTypeStorage\Business\ShipmentTypeStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStorageRepositoryInterface getRepository()
 */
class ShipmentTypeStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentTypeFacadeInterface
     */
    public function getShipmentTypeFacade(): ShipmentTypeStorageToShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentTypeStorageDependencyProvider::FACADE_SHIPMENT_TYPE);
    }
}
