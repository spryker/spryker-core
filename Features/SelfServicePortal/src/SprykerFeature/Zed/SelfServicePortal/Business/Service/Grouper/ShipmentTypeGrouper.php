<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Grouper;

class ShipmentTypeGrouper implements ShipmentTypeGrouperInterface
{
    /**
     * @param array<int, list<int>> $productShipmentTypeIds
     * @param list<\Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return array<int, list<\Generated\Shared\Transfer\ShipmentTypeTransfer>>
     */
    public function groupShipmentTypesByIdProductConcrete(
        array $productShipmentTypeIds,
        array $shipmentTypeTransfers
    ): array {
        $indexedShipmentTypes = $this->indexShipmentTypeTransfersByIdShipmentType($shipmentTypeTransfers);
        $groupedShipmentTypes = [];

        foreach ($productShipmentTypeIds as $idProductConcrete => $shipmentTypeIds) {
            foreach ($shipmentTypeIds as $idShipmentType) {
                if (!isset($indexedShipmentTypes[$idShipmentType])) {
                    continue;
                }

                $groupedShipmentTypes[$idProductConcrete][] = $indexedShipmentTypes[$idShipmentType];
            }
        }

        return $groupedShipmentTypes;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    protected function indexShipmentTypeTransfersByIdShipmentType(array $shipmentTypeTransfers): array
    {
        $indexedShipmentTypes = [];
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $indexedShipmentTypes[$shipmentTypeTransfer->getIdShipmentTypeOrFail()] = $shipmentTypeTransfer;
        }

        return $indexedShipmentTypes;
    }
}
