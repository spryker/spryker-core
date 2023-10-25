<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Business\Expander;

use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Spryker\Zed\ShipmentTypeStorage\Business\Reader\ShipmentMethodReaderInterface;

class ShipmentTypeStorageExpander implements ShipmentTypeStorageExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypeStorage\Business\Reader\ShipmentMethodReaderInterface
     */
    protected ShipmentMethodReaderInterface $shipmentMethodReader;

    /**
     * @var list<\Spryker\Zed\ShipmentTypeStorageExtension\Dependency\Plugin\ShipmentTypeStorageExpanderPluginInterface>
     */
    protected array $shipmentTypeStorageExpanderPlugins;

    /**
     * @param \Spryker\Zed\ShipmentTypeStorage\Business\Reader\ShipmentMethodReaderInterface $shipmentMethodReader
     * @param list<\Spryker\Zed\ShipmentTypeStorageExtension\Dependency\Plugin\ShipmentTypeStorageExpanderPluginInterface> $shipmentTypeStorageExpanderPlugins
     */
    public function __construct(
        ShipmentMethodReaderInterface $shipmentMethodReader,
        array $shipmentTypeStorageExpanderPlugins
    ) {
        $this->shipmentMethodReader = $shipmentMethodReader;
        $this->shipmentTypeStorageExpanderPlugins = $shipmentTypeStorageExpanderPlugins;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     * @param string $storeName
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    public function expandShipmentTypeStorageTransfers(array $shipmentTypeStorageTransfers, string $storeName): array
    {
        $shipmentMethodCollectionTransfer = $this->shipmentMethodReader->getActiveShipmentMethodCollectionTransferForStore($storeName);
        $shipmentMethodIdsGroupedByIdShipmentType = $this->getShipmentMethodIdsGroupedByIdShipmentType(
            $shipmentMethodCollectionTransfer,
        );

        foreach ($shipmentTypeStorageTransfers as $shipmentTypeStorageTransfer) {
            $shipmentTypeStorageTransfer->setShipmentMethodIds(
                $shipmentMethodIdsGroupedByIdShipmentType[$shipmentTypeStorageTransfer->getIdShipmentTypeOrFail()] ?? null,
            );
        }

        return $this->executeShipmentTypeStorageExpanderPlugins($shipmentTypeStorageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer
     *
     * @return array<int, list<int>>
     */
    protected function getShipmentMethodIdsGroupedByIdShipmentType(ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer): array
    {
        $groupedShipmentMethodTransfers = [];
        foreach ($shipmentMethodCollectionTransfer->getShipmentMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getShipmentType() === null) {
                continue;
            }

            $idShipmentType = $shipmentMethodTransfer->getShipmentTypeOrFail()->getIdShipmentTypeOrFail();
            $groupedShipmentMethodTransfers[$idShipmentType][] = $shipmentMethodTransfer->getIdShipmentMethodOrFail();
        }

        return $groupedShipmentMethodTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    protected function executeShipmentTypeStorageExpanderPlugins(array $shipmentTypeStorageTransfers): array
    {
        foreach ($this->shipmentTypeStorageExpanderPlugins as $shipmentTypeStorageExpanderPlugin) {
            $shipmentTypeStorageTransfers = $shipmentTypeStorageExpanderPlugin->expand($shipmentTypeStorageTransfers);
        }

        return $shipmentTypeStorageTransfers;
    }
}
