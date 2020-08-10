<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer;
use Generated\Shared\Transfer\RestShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;

class ShipmentMethodMapper implements ShipmentMethodMapperInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodTransfers
     * @param \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer[] $restShipmentMethodsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer[]
     */
    public function mapShipmentMethodTransfersToRestShipmentMethodsAttributesTransfers(
        ArrayObject $shipmentMethodTransfers,
        array $restShipmentMethodsAttributesTransfers = []
    ): array {
        foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
            $restShipmentMethodsAttributesTransfer = (new RestShipmentMethodsAttributesTransfer())
                ->fromArray($shipmentMethodTransfer->toArray(), true)
                ->setPrice($shipmentMethodTransfer->getStoreCurrencyPrice());

            $restShipmentMethodsAttributesTransfers[$shipmentMethodTransfer->getIdShipmentMethod()] = $restShipmentMethodsAttributesTransfer;
        }

        return $restShipmentMethodsAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function mapRestCheckoutDataResponseTransferToRestCheckoutDataResponseAttributesTransfer(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $idSelectedShipmentMethod = $restCheckoutRequestAttributesTransfer->getShipment() ?
            $restCheckoutRequestAttributesTransfer->getShipment()->getIdShipmentMethod() : null;

        if (!$idSelectedShipmentMethod) {
            return $restCheckoutDataResponseAttributesTransfer;
        }

        foreach ($restCheckoutDataTransfer->getShipmentMethods()->getMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getIdShipmentMethod() === $idSelectedShipmentMethod) {
                $restShipmentMethodTransfer = $this->mapShipmentMethodTransferToRestShipmentMethodTransfer(
                    $shipmentMethodTransfer,
                    new RestShipmentMethodTransfer()
                );

                $restCheckoutDataResponseAttributesTransfer->addSelectedShipmentMethod($restShipmentMethodTransfer);
            }
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\RestShipmentMethodTransfer $restShipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodTransfer
     */
    protected function mapShipmentMethodTransferToRestShipmentMethodTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        RestShipmentMethodTransfer $restShipmentMethodTransfer
    ): RestShipmentMethodTransfer {
        $restShipmentMethodTransfer
            ->fromArray($shipmentMethodTransfer->toArray(), true)
            ->setPrice($shipmentMethodTransfer->getStoreCurrencyPrice())
            ->setId($shipmentMethodTransfer->getIdShipmentMethod());

        return $restShipmentMethodTransfer;
    }
}
