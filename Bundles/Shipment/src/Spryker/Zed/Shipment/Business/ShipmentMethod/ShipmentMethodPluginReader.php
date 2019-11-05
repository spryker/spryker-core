<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodPluginSelectionTransfer;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

class ShipmentMethodPluginReader implements ShipmentMethodPluginReaderInterface
{
    /**
     * @var array
     */
    protected $plugins;

    /**
     * @param array $plugins
     */
    public function __construct(array $plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodPluginSelectionTransfer
     */
    public function getShipmentMethodPlugins(): ShipmentMethodPluginSelectionTransfer
    {
        $shipmentMethodPluginSelectionTransfer = new ShipmentMethodPluginSelectionTransfer();
        $shipmentMethodPluginSelectionTransfer = $this->addAvailabilityPlugins($shipmentMethodPluginSelectionTransfer);
        $shipmentMethodPluginSelectionTransfer = $this->addPricePlugins($shipmentMethodPluginSelectionTransfer);
        $shipmentMethodPluginSelectionTransfer = $this->addDeliveryTimePlugins($shipmentMethodPluginSelectionTransfer);

        return $shipmentMethodPluginSelectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodPluginSelectionTransfer $shipmentMethodPluginSelectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodPluginSelectionTransfer
     */
    protected function addAvailabilityPlugins(ShipmentMethodPluginSelectionTransfer $shipmentMethodPluginSelectionTransfer): ShipmentMethodPluginSelectionTransfer
    {
        foreach ($this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS] as $plugin) {
            $shipmentMethodPluginSelectionTransfer->addAvailabilityPluginOption(get_class($plugin));
        }

        return $shipmentMethodPluginSelectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodPluginSelectionTransfer $shipmentMethodPluginSelectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodPluginSelectionTransfer
     */
    protected function addPricePlugins(ShipmentMethodPluginSelectionTransfer $shipmentMethodPluginSelectionTransfer): ShipmentMethodPluginSelectionTransfer
    {
        foreach ($this->plugins[ShipmentDependencyProvider::PRICE_PLUGINS] as $plugin) {
            $shipmentMethodPluginSelectionTransfer->addPricePluginOption(get_class($plugin));
        }

        return $shipmentMethodPluginSelectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodPluginSelectionTransfer $shipmentMethodPluginSelectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodPluginSelectionTransfer
     */
    protected function addDeliveryTimePlugins(ShipmentMethodPluginSelectionTransfer $shipmentMethodPluginSelectionTransfer): ShipmentMethodPluginSelectionTransfer
    {
        foreach ($this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS] as $plugin) {
            $shipmentMethodPluginSelectionTransfer->addDeliveryTimePluginOption(get_class($plugin));
        }

        return $shipmentMethodPluginSelectionTransfer;
    }
}
