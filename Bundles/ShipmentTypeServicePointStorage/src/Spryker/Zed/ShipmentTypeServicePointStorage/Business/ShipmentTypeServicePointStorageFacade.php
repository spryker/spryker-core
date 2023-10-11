<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ShipmentTypeServicePointStorage\Business\ShipmentTypeServicePointStorageBusinessFactory getFactory()
 */
class ShipmentTypeServicePointStorageFacade extends AbstractFacade implements ShipmentTypeServicePointStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    public function expandShipmentTypeStoragesWithServiceType(array $shipmentTypeStorageTransfers): array
    {
        return $this->getFactory()
            ->createServiceTypeExpander()
            ->expandShipmentTypeStorages($shipmentTypeStorageTransfers);
    }
}
