<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi\Processor\Sorter;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;

class ShipmentTypeSorter implements ShipmentTypeSorterInterface
{
    /**
     * @var string
     */
    protected const SORT_FIELD_KEY = 'key';

    /**
     * @var string
     */
    protected const SORT_DIRECTION_ASC = 'ASC';

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     * @param list<\Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface> $sorts
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function sortShipmentTypeStorageCollection(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer,
        array $sorts
    ): ShipmentTypeStorageCollectionTransfer {
        foreach ($sorts as $sort) {
            if ($sort->getField() !== static::SORT_FIELD_KEY) {
                continue;
            }
            $shipmentTypeStorageCollectionTransfer = $this->sortShipmentTypeStorageCollectionByKey(
                $shipmentTypeStorageCollectionTransfer,
                $sort->getDirection(),
            );
        }

        return $shipmentTypeStorageCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     * @param string $sortDirection
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    protected function sortShipmentTypeStorageCollectionByKey(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer,
        string $sortDirection
    ): ShipmentTypeStorageCollectionTransfer {
        $shipmentTypeStorages = $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages()->getArrayCopy();
        usort(
            $shipmentTypeStorages,
            function (ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer1, ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer2) use ($sortDirection) {
                $comparison = strcmp($shipmentTypeStorageTransfer1->getKeyOrFail(), $shipmentTypeStorageTransfer2->getKeyOrFail());

                return $sortDirection === static::SORT_DIRECTION_ASC ? $comparison : -$comparison;
            },
        );

        return $shipmentTypeStorageCollectionTransfer->setShipmentTypeStorages(new ArrayObject($shipmentTypeStorages));
    }
}
