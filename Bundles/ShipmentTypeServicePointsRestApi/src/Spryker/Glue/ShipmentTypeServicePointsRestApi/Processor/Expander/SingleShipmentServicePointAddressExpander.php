<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServicePointReaderInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
class SingleShipmentServicePointAddressExpander extends AbstractServicePointAddressExpander
{
    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface
     */
    protected ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader;

    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServicePointReaderInterface
     */
    protected ServicePointReaderInterface $servicePointReader;

    /**
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServicePointReaderInterface $servicePointReader
     */
    public function __construct(
        ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader,
        ServicePointReaderInterface $servicePointReader
    ) {
        $this->shipmentTypeStorageReader = $shipmentTypeStorageReader;
        $this->servicePointReader = $servicePointReader;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function expandRestCheckoutRequestAttributesTransfer(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutRequestAttributesTransfer {
        $restShipmentTransfer = $restCheckoutRequestAttributesTransfer->getShipmentOrFail();
        $applicableShipmentTypeStorageTransfers = $this->shipmentTypeStorageReader->getApplicableShipmentTypeStorageTransfersIndexedByIdShipmentMethod(
            [$restShipmentTransfer->getIdShipmentMethodOrFail()],
        );
        if (
            !$restShipmentTransfer->getIdShipmentMethod()
            || !$this->isApplicableShipmentMethod($restShipmentTransfer->getIdShipmentMethodOrFail(), $applicableShipmentTypeStorageTransfers)
        ) {
            return $restCheckoutRequestAttributesTransfer;
        }

        $servicePointStorageCollectionTransfer = $this->servicePointReader->getServicePointStorageTransfersByUuids(
            [$restCheckoutRequestAttributesTransfer->getServicePoints()->getIterator()->current()->getIdServicePointOrFail()],
        );

        $servicePointStorageTransfer = $servicePointStorageCollectionTransfer->getServicePointStorages()->getIterator()->current();
        $restCheckoutRequestAttributesTransfer->setShippingAddress(
            $this->createRestShippingAddressTransfer(
                $restCheckoutRequestAttributesTransfer->getCustomerOrFail(),
                $servicePointStorageTransfer->getAddressOrFail(),
            ),
        );

        return $restCheckoutRequestAttributesTransfer;
    }
}
