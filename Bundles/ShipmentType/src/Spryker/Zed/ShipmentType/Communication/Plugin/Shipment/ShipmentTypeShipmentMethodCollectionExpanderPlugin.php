<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Communication\Plugin\Shipment;

use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ShipmentType\ShipmentTypeConfig getConfig()
 * @method \Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface getFacade()
 */
class ShipmentTypeShipmentMethodCollectionExpanderPlugin extends AbstractPlugin implements ShipmentMethodCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ShipmentMethodCollectionTransfer.shipmentMethod.idShipmentMethod` to be set.
     * - Expands `ShipmentMethodCollectionTransfer.shipmentMethod` with shipment type.
     * - Does nothing if `ShipmentMethodCollectionTransfer.shipmentMethod` doesn't have shipment type relation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function expand(ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer): ShipmentMethodCollectionTransfer
    {
        return $this->getFacade()->expandShipmentMethodCollectionWithShipmentType($shipmentMethodCollectionTransfer);
    }
}
