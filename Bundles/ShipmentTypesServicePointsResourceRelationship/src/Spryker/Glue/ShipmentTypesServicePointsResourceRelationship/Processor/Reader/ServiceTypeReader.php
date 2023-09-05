<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Reader;

use Generated\Shared\Transfer\ServiceTypeResourceConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeResourceCriteriaTransfer;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Dependency\Resource\ShipmentTypesServicePointsResourceRelationshipToServicePointsRestApiResourceInterface;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Builder\ServiceTypeResourceBuilderInterface;

class ServiceTypeReader implements ServiceTypeReaderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Dependency\Resource\ShipmentTypesServicePointsResourceRelationshipToServicePointsRestApiResourceInterface
     */
    protected ShipmentTypesServicePointsResourceRelationshipToServicePointsRestApiResourceInterface $servicePointsRestApiResource;

    /**
     * @var \Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Builder\ServiceTypeResourceBuilderInterface
     */
    protected ServiceTypeResourceBuilderInterface $serviceTypeResourceBuilder;

    /**
     * @param \Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Dependency\Resource\ShipmentTypesServicePointsResourceRelationshipToServicePointsRestApiResourceInterface $servicePointsRestApiResource
     * @param \Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Builder\ServiceTypeResourceBuilderInterface $serviceTypeResourceBuilder
     */
    public function __construct(
        ShipmentTypesServicePointsResourceRelationshipToServicePointsRestApiResourceInterface $servicePointsRestApiResource,
        ServiceTypeResourceBuilderInterface $serviceTypeResourceBuilder
    ) {
        $this->servicePointsRestApiResource = $servicePointsRestApiResource;
        $this->serviceTypeResourceBuilder = $serviceTypeResourceBuilder;
    }

    /**
     * @param list<string> $serviceTypeUuids
     *
     * @return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    public function getServiceTypeRestResourcesIndexedByServiceTypeUuid(array $serviceTypeUuids): array
    {
        $serviceTypeResourceCriteriaTransfer = $this->createServiceTypeResourceCriteriaTransfer($serviceTypeUuids);
        $serviceTypeResourceCollectionTransfer = $this->servicePointsRestApiResource
            ->getServiceTypeResourceCollection($serviceTypeResourceCriteriaTransfer);

        $serviceTypeRestResourcesIndexedByServiceTypeUuid = [];
        foreach ($serviceTypeResourceCollectionTransfer->getServiceTypeResources() as $serviceTypeGlueResourceTransfer) {
            $serviceTypeRestResourcesIndexedByServiceTypeUuid[$serviceTypeGlueResourceTransfer->getIdOrFail()] = $this->serviceTypeResourceBuilder
                ->createServiceTypesRestResource(
                    $serviceTypeGlueResourceTransfer,
                );
        }

        return $serviceTypeRestResourcesIndexedByServiceTypeUuid;
    }

    /**
     * @param list<string> $serviceTypeUuids
     *
     * @return \Generated\Shared\Transfer\ServiceTypeResourceCriteriaTransfer
     */
    protected function createServiceTypeResourceCriteriaTransfer(array $serviceTypeUuids): ServiceTypeResourceCriteriaTransfer
    {
        $serviceTypeResourceConditionsTransfer = (new ServiceTypeResourceConditionsTransfer())->setUuids($serviceTypeUuids);

        return (new ServiceTypeResourceCriteriaTransfer())->setServiceTypeResourceConditions($serviceTypeResourceConditionsTransfer);
    }
}
