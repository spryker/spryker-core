<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointAddressResponseBuilderInterface;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig;

class ServicePointAddressReader implements ServicePointAddressReaderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointStorageReaderInterface
     */
    protected ServicePointStorageReaderInterface $servicePointStorageReader;

    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointAddressResponseBuilderInterface
     */
    protected ServicePointAddressResponseBuilderInterface $servicePointAddressResponseBuilder;

    /**
     * @param \Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointStorageReaderInterface $servicePointStorageReader
     * @param \Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointAddressResponseBuilderInterface $servicePointAddressResponseBuilder
     */
    public function __construct(
        ServicePointStorageReaderInterface $servicePointStorageReader,
        ServicePointAddressResponseBuilderInterface $servicePointAddressResponseBuilder
    ) {
        $this->servicePointStorageReader = $servicePointStorageReader;
        $this->servicePointAddressResponseBuilder = $servicePointAddressResponseBuilder;
    }

    /**
     * @param list<string> $servicePointUuids
     *
     * @return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    public function getServicePointAddressRestResourcesIndexedByServicePointUuid(array $servicePointUuids): array
    {
        $servicePointStorageCollectionTransfer = $this->servicePointStorageReader->getServicePointStorageCollection($servicePointUuids);
        if (!count($servicePointStorageCollectionTransfer->getServicePointStorages())) {
            return [];
        }

        return $this->servicePointAddressResponseBuilder
            ->createServicePointAddressRestResourcesIndexedByServicePointUuid($servicePointStorageCollectionTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getServicePointAddress(RestRequestInterface $restRequest): RestResponseInterface
    {
        $servicePointsResource = $restRequest->findParentResourceByType(ServicePointsRestApiConfig::RESOURCE_SERVICE_POINTS);
        if (!$servicePointsResource || !$servicePointsResource->getId()) {
            return $this->servicePointAddressResponseBuilder
                ->createServicePointAddressServicePointIsNotSpecifiedErrorResponse($restRequest->getMetadata()->getLocale());
        }

        $servicePointStorageTransfer = $this->servicePointStorageReader->findServicePointStorage($servicePointsResource->getId());
        if (
            !$servicePointStorageTransfer
            || !$servicePointStorageTransfer->getAddress()
            || $servicePointStorageTransfer->getAddressOrFail()->getUuidOrFail() !== $restRequest->getResource()->getId()
        ) {
            return $this->servicePointAddressResponseBuilder->createServicePointAddressNotFoundErrorResponse($restRequest->getMetadata()->getLocale());
        }

        return $this->servicePointAddressResponseBuilder->createServicePointAddressRestResponse($servicePointStorageTransfer);
    }
}
