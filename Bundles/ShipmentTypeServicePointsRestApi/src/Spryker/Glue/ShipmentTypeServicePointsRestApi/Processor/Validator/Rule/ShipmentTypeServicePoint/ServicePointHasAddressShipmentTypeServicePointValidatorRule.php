<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToStoreClientInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServicePointReaderInterface;

class ServicePointHasAddressShipmentTypeServicePointValidatorRule implements ShipmentTypeServicePointValidatorRuleInterface
{
    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServicePointReaderInterface
     */
    protected ServicePointReaderInterface $servicePointReader;

    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToStoreClientInterface
     */
    protected ShipmentTypeServicePointsRestApiToStoreClientInterface $storeClient;

    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface
     */
    protected RestErrorMessageCreatorInterface $restErrorMessageCreator;

    /**
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServicePointReaderInterface $servicePointReader
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToStoreClientInterface $storeClient
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface $restErrorMessageCreator
     */
    public function __construct(
        ServicePointReaderInterface $servicePointReader,
        ShipmentTypeServicePointsRestApiToStoreClientInterface $storeClient,
        RestErrorMessageCreatorInterface $restErrorMessageCreator
    ) {
        $this->servicePointReader = $servicePointReader;
        $this->storeClient = $storeClient;
        $this->restErrorMessageCreator = $restErrorMessageCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validate(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestErrorCollectionTransfer {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        $servicePointUuids = $this->extractServicePointUuids($restCheckoutRequestAttributesTransfer);
        if ($servicePointUuids === []) {
            return $restErrorCollectionTransfer;
        }

        $servicePointStorageCollectionTransfer = $this->servicePointReader->getServicePointStorageTransfersByUuids(
            $servicePointUuids,
        );
        foreach ($servicePointStorageCollectionTransfer->getServicePointStorages() as $servicePointStorageTransfer) {
            if ($servicePointStorageTransfer->getAddress()) {
                continue;
            }

            $restErrorCollectionTransfer->addRestError(
                $this->restErrorMessageCreator->createServicePointAddressMissingErrorMessage(
                    $servicePointStorageTransfer->getUuidOrFail(),
                ),
            );
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return list<string>
     */
    protected function extractServicePointUuids(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): array
    {
        $servicePointUuids = [];
        foreach ($restCheckoutRequestAttributesTransfer->getServicePoints() as $restServicePointTransfer) {
            $servicePointUuids[] = $restServicePointTransfer->getIdServicePointOrFail();
        }

        return $servicePointUuids;
    }
}
