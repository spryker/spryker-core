<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

/**
 * @implements \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\DataTransformerInterface<\ArrayObject<int, \Generated\Shared\Transfer\ShipmentTypeTransfer>, array<string>>
 */
class ShipmentTypesDataTransformer implements DataTransformerInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentTypeTransfer>|mixed $shipmentTypeTransfers
     *
     * @return array<string>
     */
    public function transform(mixed $shipmentTypeTransfers): array
    {
        if (!$shipmentTypeTransfers) {
            return [];
        }

        $shipmentTypeUuids = [];
        foreach ($shipmentTypeTransfers->getArrayCopy() as $shipmentTypeTransfer) {
            if ($shipmentTypeTransfer->getUuid() !== null) {
                $shipmentTypeUuids[] = $shipmentTypeTransfer->getUuid();
            }
        }

        return $shipmentTypeUuids;
    }

    /**
     * @param mixed|array<string>|null $shipmentTypeUuids
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    public function reverseTransform(mixed $shipmentTypeUuids): ArrayObject
    {
        if (!$shipmentTypeUuids) {
            return new ArrayObject();
        }

        $shipmentTypeTransfers = new ArrayObject();
        foreach ($shipmentTypeUuids as $shipmentTypeUuid) {
            if ($shipmentTypeUuid === null) {
                continue;
            }

            $shipmentTypeTransfers->append(
                (new ShipmentTypeTransfer())->setUuid($shipmentTypeUuid),
            );
        }

        return $shipmentTypeTransfers;
    }
}
