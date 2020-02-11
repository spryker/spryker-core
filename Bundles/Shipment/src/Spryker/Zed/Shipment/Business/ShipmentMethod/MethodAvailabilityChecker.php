<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodAvailabilityPluginInterface;

class MethodAvailabilityChecker implements MethodAvailabilityCheckerInterface
{
    /**
     * @var \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodAvailabilityPluginInterface[]
     */
    protected $shipmentMethodAvailabilityPlugins;

    /**
     * @param \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodAvailabilityPluginInterface[] $shipmentMethodAvailabilityPlugins
     */
    public function __construct(array $shipmentMethodAvailabilityPlugins)
    {
        $this->shipmentMethodAvailabilityPlugins = $shipmentMethodAvailabilityPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer|null $shipmentGroupTransfer
     *
     * @return bool
     */
    public function isShipmentMethodAvailableForShipmentGroup(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        QuoteTransfer $quoteTransfer,
        ?ShipmentGroupTransfer $shipmentGroupTransfer = null
    ): bool {
        if (!$this->isSetAvailabilityPlugin($shipmentMethodTransfer)) {
            return true;
        }

        $availabilityPlugin = $this->getAvailabilityPlugin($shipmentMethodTransfer);
        if (!$availabilityPlugin instanceof ShipmentMethodAvailabilityPluginInterface) {
            return $availabilityPlugin->isAvailable($quoteTransfer);
        }

        if ($shipmentGroupTransfer === null) {
            return true;
        }

        return $availabilityPlugin->isAvailable($shipmentGroupTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    protected function isSetAvailabilityPlugin(ShipmentMethodTransfer $shipmentMethodTransfer): bool
    {
        return isset($this->shipmentMethodAvailabilityPlugins[$shipmentMethodTransfer->getAvailabilityPlugin()]);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodAvailabilityPluginInterface|\Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface
     */
    protected function getAvailabilityPlugin(ShipmentMethodTransfer $shipmentMethodTransfer)
    {
        return $this->shipmentMethodAvailabilityPlugins[$shipmentMethodTransfer->getAvailabilityPlugin()];
    }
}
