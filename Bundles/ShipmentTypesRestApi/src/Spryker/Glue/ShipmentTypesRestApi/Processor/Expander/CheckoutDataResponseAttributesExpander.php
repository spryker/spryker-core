<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestShipmentTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

/**
 * @deprecated Will be removed without replacement.
 */
class CheckoutDataResponseAttributesExpander implements CheckoutDataResponseAttributesExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function expandCheckoutDataResponseAttributesWithSelectedShipmentTypes(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        if (!$restCheckoutRequestAttributesTransfer->getShipment()) {
            return $restCheckoutDataResponseAttributesTransfer;
        }

        return $restCheckoutDataResponseAttributesTransfer->setSelectedShipmentTypes(
            $this->getSelectedRestShipmentTypeTransfers($restCheckoutDataTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\RestShipmentTypeTransfer>
     */
    protected function getSelectedRestShipmentTypeTransfers(RestCheckoutDataTransfer $restCheckoutDataTransfer): ArrayObject
    {
        $restShipmentTypeTransfers = [];
        foreach ($restCheckoutDataTransfer->getQuoteOrFail()->getItems() as $itemTransfer) {
            $shipmentTypeTransfer = $itemTransfer->getShipmentType();
            if (!$shipmentTypeTransfer || !$shipmentTypeTransfer->getUuid()) {
                continue;
            }
            $restShipmentTypeTransfers[$shipmentTypeTransfer->getUuidOrFail()] = $this->mapShipmentTypeTransferToRestShipmentTypeTransfer(
                $shipmentTypeTransfer,
                new RestShipmentTypeTransfer(),
            );
        }

        return new ArrayObject(array_values($restShipmentTypeTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param \Generated\Shared\Transfer\RestShipmentTypeTransfer $restShipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentTypeTransfer
     */
    protected function mapShipmentTypeTransferToRestShipmentTypeTransfer(
        ShipmentTypeTransfer $shipmentTypeTransfer,
        RestShipmentTypeTransfer $restShipmentTypeTransfer
    ): RestShipmentTypeTransfer {
        $restShipmentTypeTransfer->fromArray($shipmentTypeTransfer->toArray(), true);

        return $restShipmentTypeTransfer->setId($shipmentTypeTransfer->getUuidOrFail());
    }
}
