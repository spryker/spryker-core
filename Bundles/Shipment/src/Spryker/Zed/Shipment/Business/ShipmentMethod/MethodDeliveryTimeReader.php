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
use Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodDeliveryTimePluginInterface;

class MethodDeliveryTimeReader implements MethodDeliveryTimeReaderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodDeliveryTimePluginInterface[]
     */
    protected $shipmentMethodDeliveryTimePlugins;

    /**
     * @param \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodDeliveryTimePluginInterface[] $shipmentMethodDeliveryTimePlugins
     */
    public function __construct(array $shipmentMethodDeliveryTimePlugins)
    {
        $this->shipmentMethodDeliveryTimePlugins = $shipmentMethodDeliveryTimePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer|null $shipmentGroupTransfer
     *
     * @return int|null
     */
    public function getDeliveryTimeForShippingGroup(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        QuoteTransfer $quoteTransfer,
        ?ShipmentGroupTransfer $shipmentGroupTransfer = null
    ): ?int {
        if (!$this->issetDeliveryTimePlugin($shipmentMethodTransfer)) {
            return null;
        }

        $deliveryTimePlugin = $this->getDeliveryTimePlugin($shipmentMethodTransfer);
        if (!$deliveryTimePlugin instanceof ShipmentMethodDeliveryTimePluginInterface) {
            /**
             * @deprecated Exists for Backward Compatibility reasons only.
             */
            return $deliveryTimePlugin->getTime($quoteTransfer);
        }

        if ($shipmentGroupTransfer === null) {
            return null;
        }

        return $deliveryTimePlugin->getTime($shipmentGroupTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    protected function issetDeliveryTimePlugin(ShipmentMethodTransfer $shipmentMethodTransfer): bool
    {
        return isset($this->shipmentMethodDeliveryTimePlugins[$shipmentMethodTransfer->getDeliveryTimePlugin()]);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodDeliveryTimePluginInterface|\Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodDeliveryTimePluginInterface
     */
    protected function getDeliveryTimePlugin(ShipmentMethodTransfer $shipmentMethodTransfer)
    {
        return $this->shipmentMethodDeliveryTimePlugins[$shipmentMethodTransfer->getDeliveryTimePlugin()];
    }
}
