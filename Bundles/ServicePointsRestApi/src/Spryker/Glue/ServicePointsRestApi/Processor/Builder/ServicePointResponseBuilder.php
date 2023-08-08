<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Builder;

use Generated\Shared\Transfer\RestServicePointsAttributesTransfer;
use Generated\Shared\Transfer\ServicePointSearchCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServicePointMapperInterface;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig;

class ServicePointResponseBuilder implements ServicePointResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected RestResourceBuilderInterface $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Processor\Builder\ErrorResponseBuilderInterface
     */
    protected ErrorResponseBuilderInterface $errorResponseBuilder;

    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServicePointMapperInterface
     */
    protected ServicePointMapperInterface $servicePointMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ServicePointsRestApi\Processor\Builder\ErrorResponseBuilderInterface $errorResponseBuilder
     * @param \Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServicePointMapperInterface $servicePointMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ErrorResponseBuilderInterface $errorResponseBuilder,
        ServicePointMapperInterface $servicePointMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->errorResponseBuilder = $errorResponseBuilder;
        $this->servicePointMapper = $servicePointMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageTransfer $servicePointStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createServicePointRestResponse(
        ServicePointStorageTransfer $servicePointStorageTransfer
    ): RestResponseInterface {
        $restServicePointAttributesTransfer = $this->servicePointMapper->mapServicePointStorageTransferToRestServicePointsAttributesTransfer(
            $servicePointStorageTransfer,
            new RestServicePointsAttributesTransfer(),
        );

        $servicePointRestResource = $this->createServicePointRestResource(
            $restServicePointAttributesTransfer,
            $servicePointStorageTransfer->getUuidOrFail(),
        );

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addResource($servicePointRestResource);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointSearchCollectionTransfer $servicePointSearchCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createServicePointCollectionRestResponse(
        ServicePointSearchCollectionTransfer $servicePointSearchCollectionTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse(
            $servicePointSearchCollectionTransfer->getNbResultsOrFail(),
            $servicePointSearchCollectionTransfer->getItemsPerPageOrFail(),
        );

        foreach ($servicePointSearchCollectionTransfer->getServicePoints() as $servicePointSearchTransfer) {
            $restServicePointAttributesTransfer = $this->servicePointMapper->mapServicePointSearchTransferToRestServicePointsAttributesTransfer(
                $servicePointSearchTransfer,
                new RestServicePointsAttributesTransfer(),
            );

            $servicePointRestResource = $this->createServicePointRestResource(
                $restServicePointAttributesTransfer,
                $servicePointSearchTransfer->getUuidOrFail(),
            );

            $restResponse->addResource($servicePointRestResource);
        }

        return $restResponse;
    }

    /**
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createServicePointNotFoundErrorResponse(string $localeName): RestResponseInterface
    {
        return $this->errorResponseBuilder->createErrorResponse(
            ServicePointsRestApiConfig::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND,
            $localeName,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestServicePointsAttributesTransfer $restServicePointAttributesTransfer
     * @param string $servicePointUuid
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createServicePointRestResource(
        RestServicePointsAttributesTransfer $restServicePointAttributesTransfer,
        string $servicePointUuid
    ): RestResourceInterface {
        return $this->restResourceBuilder->createRestResource(
            ServicePointsRestApiConfig::RESOURCE_SERVICE_POINTS,
            $servicePointUuid,
            $restServicePointAttributesTransfer,
        );
    }
}
