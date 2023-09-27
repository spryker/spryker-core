<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Validator;

use ArrayObject;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageConditionsTransfer;
use Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientInterface;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToStoreClientInterface;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ServicePointCheckoutRequestAttributesValidator implements ServicePointCheckoutRequestAttributesValidatorInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientInterface
     */
    protected ServicePointsRestApiToServicePointStorageClientInterface $servicePointStorageClient;

    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToStoreClientInterface
     */
    protected ServicePointsRestApiToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientInterface $servicePointStorageClient
     * @param \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToStoreClientInterface $storeClient
     */
    public function __construct(
        ServicePointsRestApiToServicePointStorageClientInterface $servicePointStorageClient,
        ServicePointsRestApiToStoreClientInterface $storeClient
    ) {
        $this->servicePointStorageClient = $servicePointStorageClient;
        $this->storeClient = $storeClient;
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

        if (!$restCheckoutRequestAttributesTransfer->getServicePoints()->count()) {
            return $restErrorCollectionTransfer;
        }
        $restErrorCollectionTransfer = $this->validateServicePointItemsUniqueness(
            $restCheckoutRequestAttributesTransfer->getServicePoints(),
            $restErrorCollectionTransfer,
        );

        return $this->validateServicePointsExistenceForCurrentStore(
            $restCheckoutRequestAttributesTransfer->getServicePoints(),
            $restErrorCollectionTransfer,
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\RestServicePointTransfer> $restServicePointTransfers
     * @param \Generated\Shared\Transfer\RestErrorCollectionTransfer $restErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function validateServicePointItemsUniqueness(
        ArrayObject $restServicePointTransfers,
        RestErrorCollectionTransfer $restErrorCollectionTransfer
    ): RestErrorCollectionTransfer {
        $servicePointsItems = [];
        foreach ($restServicePointTransfers as $restServicePointTransfer) {
            $servicePointsItems = array_merge($servicePointsItems, $restServicePointTransfer->getItems());
        }

        if (count(array_unique($servicePointsItems)) < count($servicePointsItems)) {
            $restErrorCollectionTransfer = $this->addRestErrorMessage(
                $restErrorCollectionTransfer,
                ServicePointsRestApiConfig::RESPONSE_CODE_SERVICE_POINT_ITEM_IS_DUPLICATED,
                ServicePointsRestApiConfig::RESPONSE_DETAILS_SERVICE_POINT_ITEM_IS_DUPLICATED,
            );
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\RestServicePointTransfer> $restServicePointTransfers
     * @param \Generated\Shared\Transfer\RestErrorCollectionTransfer $restErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function validateServicePointsExistenceForCurrentStore(
        ArrayObject $restServicePointTransfers,
        RestErrorCollectionTransfer $restErrorCollectionTransfer
    ): RestErrorCollectionTransfer {
        $servicePointUuids = $this->extractServicePointUuidsFromRestServicePointTransfers($restServicePointTransfers);

        $servicePointStorageCollection = $this->servicePointStorageClient->getServicePointStorageCollection(
            $this->getServicePointStorageCriteriaTransfer($servicePointUuids),
        );
        $storageServicePointUuidsIndexedByServicePointUuids = $this->getServicePointUuidsIndexedByServicePointUuids(
            $servicePointStorageCollection,
        );

        foreach ($servicePointUuids as $servicePointUuid) {
            if (!isset($storageServicePointUuidsIndexedByServicePointUuids[$servicePointUuid])) {
                $restErrorCollectionTransfer = $this->addRestErrorMessage(
                    $restErrorCollectionTransfer,
                    ServicePointsRestApiConfig::RESPONSE_CODE_SERVICE_POINT_IS_UNAVAILABLE,
                    ServicePointsRestApiConfig::RESPONSE_DETAILS_SERVICE_POINT_IS_UNAVAILABLE,
                );
            }
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer $servicePointStorageCollection
     *
     * @return array<string, string>
     */
    protected function getServicePointUuidsIndexedByServicePointUuids(
        ServicePointStorageCollectionTransfer $servicePointStorageCollection
    ): array {
        $storageServicePointUuids = [];
        foreach ($servicePointStorageCollection->getServicePointStorages() as $servicePointStorageTransfer) {
            $servicePointUuid = $servicePointStorageTransfer->getUuidOrFail();
            $storageServicePointUuids[$servicePointUuid] = $servicePointUuid;
        }

        return $storageServicePointUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\RestErrorCollectionTransfer $restErrorCollectionTransfer
     * @param string $responseCode
     * @param string $responseDetails
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function addRestErrorMessage(
        RestErrorCollectionTransfer $restErrorCollectionTransfer,
        string $responseCode,
        string $responseDetails
    ): RestErrorCollectionTransfer {
        return $restErrorCollectionTransfer->addRestError(
            (new RestErrorMessageTransfer())
                ->setCode($responseCode)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setDetail($responseDetails),
        );
    }

    /**
     * @param list<string> $servicePointUuids
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer
     */
    protected function getServicePointStorageCriteriaTransfer(array $servicePointUuids): ServicePointStorageCriteriaTransfer
    {
        return (new ServicePointStorageCriteriaTransfer())->setServicePointStorageConditions(
            (new ServicePointStorageConditionsTransfer())
                ->setUuids($servicePointUuids)
                ->setStoreName($this->storeClient->getCurrentStore()->getName()),
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\RestServicePointTransfer> $restServicePointTransfers
     *
     * @return list<string>
     */
    protected function extractServicePointUuidsFromRestServicePointTransfers(
        ArrayObject $restServicePointTransfers
    ): array {
        $servicePointUuids = [];

        foreach ($restServicePointTransfers as $restServicePointTransfer) {
            $servicePointUuids[] = $restServicePointTransfer->getIdServicePointOrFail();
        }

        return $servicePointUuids;
    }
}
