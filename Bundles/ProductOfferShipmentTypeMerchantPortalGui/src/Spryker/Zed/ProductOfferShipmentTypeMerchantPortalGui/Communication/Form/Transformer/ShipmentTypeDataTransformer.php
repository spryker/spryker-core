<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Communication\Form\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Symfony\Component\Form\DataTransformerInterface;

class ShipmentTypeDataTransformer implements DataTransformerInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer>|mixed $value
     *
     * @return list<int>|null
     */
    public function transform(mixed $value): ?array
    {
        if ($value === null) {
            return null;
        }

        $shipmentTypeIds = [];
        foreach ($value as $shipmentTypeTransfer) {
            $shipmentTypeIds[] = $shipmentTypeTransfer->getIdShipmentTypeOrFail();
        }

        return $shipmentTypeIds;
    }

    /**
     * @param list<int>|mixed|null $value
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer>|null
     */
    public function reverseTransform(mixed $value): ?ArrayObject
    {
        if ($value === null) {
            return null;
        }

        $shipmentTypeTransfers = new ArrayObject();
        foreach ($value as $idShipmentType) {
            $shipmentTypeTransfers->append(
                (new ShipmentTypeTransfer())->setIdShipmentType($idShipmentType),
            );
        }

        return $shipmentTypeTransfers;
    }
}
