<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestServicePointsAttributesTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServicePointMapperInterface;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig;

class ServicePointByCheckoutDataResourceRelationshipExpander implements ServicePointByCheckoutDataResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServicePointMapperInterface
     */
    protected ServicePointMapperInterface $servicePointMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected RestResourceBuilderInterface $restResourceBuilder;

    /**
     * @param \Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServicePointMapperInterface $servicePointMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        ServicePointMapperInterface $servicePointMapper,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->servicePointMapper = $servicePointMapper;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param list<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $restResources
     *
     * @return void
     */
    public function addServicePointsResourceRelationships(array $restResources): void
    {
        foreach ($restResources as $restResource) {
            $restCheckoutDataTransfer = $restResource->getPayload();
            if (!$restCheckoutDataTransfer instanceof RestCheckoutDataTransfer) {
                continue;
            }

            $this->addServicePointResourceRelationships($restResource, $restCheckoutDataTransfer->getServicePoints());
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     *
     * @return void
     */
    protected function addServicePointResourceRelationships(RestResourceInterface $restResource, ArrayObject $servicePointTransfers): void
    {
        foreach ($servicePointTransfers as $servicePointTransfer) {
            $restResource->addRelationship(
                $this->createServicePointRestResource($servicePointTransfer),
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createServicePointRestResource(ServicePointTransfer $servicePointTransfer): RestResourceInterface
    {
        $restServicePointsAttributesTransfer = $this->servicePointMapper->mapServicePointTransferToRestServicePointsAttributesTransfer(
            $servicePointTransfer,
            new RestServicePointsAttributesTransfer(),
        );

        return $this->restResourceBuilder->createRestResource(
            ServicePointsRestApiConfig::RESOURCE_SERVICE_POINTS,
            $servicePointTransfer->getUuidOrFail(),
            $restServicePointsAttributesTransfer,
        );
    }
}
