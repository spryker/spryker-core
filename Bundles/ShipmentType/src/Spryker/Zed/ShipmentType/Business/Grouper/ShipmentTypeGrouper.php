<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Grouper;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer;

class ShipmentTypeGrouper implements ShipmentTypeGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer $shipmentTypeCollectionResponseTransfer
     *
     * @return list<\ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer>>
     */
    public function groupShipmentTypeTransfersByValidity(ShipmentTypeCollectionResponseTransfer $shipmentTypeCollectionResponseTransfer): array
    {
        $erroredEntityIdentifiers = $this->extractEntityIdentifiersFromErrorTransfers(
            $shipmentTypeCollectionResponseTransfer->getErrors(),
        );

        $validShipmentTypeTransfers = new ArrayObject();
        $invalidShipmentTypeTransfers = new ArrayObject();

        foreach ($shipmentTypeCollectionResponseTransfer->getShipmentTypes() as $entityIdentifier => $shipmentTypeTransfer) {
            if (isset($erroredEntityIdentifiers[$entityIdentifier])) {
                $invalidShipmentTypeTransfers->offsetSet($entityIdentifier, $shipmentTypeTransfer);

                continue;
            }

            $validShipmentTypeTransfers->offsetSet($entityIdentifier, $shipmentTypeTransfer);
        }

        return [$validShipmentTypeTransfers, $invalidShipmentTypeTransfers];
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $baseShipmentTypeTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $additionalShipmentTypeTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    public function mergeShipmentTypeTransfers(ArrayObject $baseShipmentTypeTransfers, ArrayObject $additionalShipmentTypeTransfers): ArrayObject
    {
        foreach ($additionalShipmentTypeTransfers as $entityIdentifier => $shipmentTypeTransfers) {
            $baseShipmentTypeTransfers->offsetSet($entityIdentifier, $shipmentTypeTransfers);
        }

        return $baseShipmentTypeTransfers;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return array<string, string>
     */
    protected function extractEntityIdentifiersFromErrorTransfers(ArrayObject $errorTransfers): array
    {
        $entityIdentifiers = [];
        foreach ($errorTransfers as $errorTransfer) {
            $entityIdentifiers[$errorTransfer->getEntityIdentifierOrFail()] = $errorTransfer->getEntityIdentifierOrFail();
        }

        return $entityIdentifiers;
    }
}
