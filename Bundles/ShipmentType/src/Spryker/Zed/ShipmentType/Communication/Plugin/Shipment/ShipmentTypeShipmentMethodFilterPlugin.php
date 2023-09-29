<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Communication\Plugin\Shipment;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodFilterPluginInterface;

/**
 * @method \Spryker\Zed\ShipmentType\ShipmentTypeConfig getConfig()
 * @method \Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface getFacade()
 */
class ShipmentTypeShipmentMethodFilterPlugin extends AbstractPlugin implements ShipmentMethodFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.store.name` transfer property to be set.
     * - Requires `ShipmentGroupTransfer.availableShipmentMethods.methods.idShipmentMethod` transfer property to be set.
     * - Expects `ShipmentGroupTransfer.items.shipmentType.uuid` transfer property to be provided.
     * - Expects `ShipmentGroupTransfer.items.shipment.method.shipmentType.uuid` transfer property to be provided.
     * - Filters out shipment methods that have relation to shipment types which are not active or not available for store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function filterShipmentMethods(ShipmentGroupTransfer $shipmentGroupTransfer, QuoteTransfer $quoteTransfer): ArrayObject
    {
        return $this->getFacade()->filterShipmentGroupMethods($shipmentGroupTransfer, $quoteTransfer);
    }
}
