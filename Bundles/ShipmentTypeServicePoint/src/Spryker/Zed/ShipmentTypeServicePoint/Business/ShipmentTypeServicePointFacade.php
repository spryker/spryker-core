<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePoint\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ShipmentTypeServicePoint\Business\ShipmentTypeServicePointBusinessFactory getFactory()
 * @method \Spryker\Zed\ShipmentTypeServicePoint\Persistence\ShipmentTypeServicePointRepositoryInterface getRepository()
 */
class ShipmentTypeServicePointFacade extends AbstractFacade implements ShipmentTypeServicePointFacadeInterface
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
            ->expandShipmentTypeStoragesWithServiceType($shipmentTypeStorageTransfers);
    }
}
