<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\ShipmentExtension\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface;

class MethodAvailabilityChecker implements MethodAvailabilityCheckerInterface
{
    /**
     * @var \Spryker\Zed\ShipmentExtension\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface[]
     */
    protected $shipmentMethodAvailabilityPlugins;

    /**
     * @param \Spryker\Zed\ShipmentExtension\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface[] $shipmentMethodAvailabilityPlugins
     */
    public function __construct(array $shipmentMethodAvailabilityPlugins)
    {
        $this->shipmentMethodAvailabilityPlugins = $shipmentMethodAvailabilityPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isShipmentMethodAvailableForShipmentGroup(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        ShipmentGroupTransfer $shipmentGroupTransfer,
        QuoteTransfer $quoteTransfer
    ): bool {
        if (!$this->isSetAvailabilityPlugin($shipmentMethodTransfer)) {
            return true;
        }

        $availabilityPlugin = $this->getAvailabilityPlugin($shipmentMethodTransfer);

        if ($availabilityPlugin instanceof ShipmentMethodAvailabilityPluginInterface) {
            return $availabilityPlugin->isAvailable($shipmentGroupTransfer, $quoteTransfer);
        }

        return $availabilityPlugin->isAvailable($quoteTransfer);
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
     * @return \Spryker\Zed\ShipmentExtension\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface|\Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface
     */
    protected function getAvailabilityPlugin(ShipmentMethodTransfer $shipmentMethodTransfer)
    {
        return $this->shipmentMethodAvailabilityPlugins[$shipmentMethodTransfer->getAvailabilityPlugin()];
    }
}
