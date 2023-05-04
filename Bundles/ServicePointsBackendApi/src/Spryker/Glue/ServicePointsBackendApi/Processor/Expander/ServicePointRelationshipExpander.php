<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Expander;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionTransfer;
use Generated\Shared\Transfer\ServicePointAddressConditionsTransfer;
use Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServicePointRelationshipExpander implements ServicePointRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface
     */
    protected ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapperInterface
     */
    protected ServicePointAddressMapperInterface $servicePointAddressMapper;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapperInterface $servicePointAddressMapper
     */
    public function __construct(
        ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade,
        ServicePointAddressMapperInterface $servicePointAddressMapper
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->servicePointAddressMapper = $servicePointAddressMapper;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addServicePointAddressesResourceRelationships(
        array $glueResourceTransfers,
        GlueRequestTransfer $glueRequestTransfer
    ): void {
        $servicePointUuids = $this->extractServicePointUuidsFromGlueResourceTransfers($glueResourceTransfers);
        $servicePointAddressCollectionTransfer = $this->servicePointFacade->getServicePointAddressCollection(
            $this->createServicePointAddressCriteria($servicePointUuids),
        );

        $servicePointAddressTransfersIndexedByServicePointUuid = $this->getServicePointAddressesIndexedByServicePointUuid($servicePointAddressCollectionTransfer);

        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            if (!$this->isApplicableResource($glueResourceTransfer)) {
                continue;
            }

            $servicePointUuid = $glueResourceTransfer->getIdOrFail();

            if (!isset($servicePointAddressTransfersIndexedByServicePointUuid[$servicePointUuid])) {
                continue;
            }

            $this->addServicePointAddressesResourceRelationshipToGlueResourceTransfer(
                $glueResourceTransfer,
                $servicePointAddressTransfersIndexedByServicePointUuid[$servicePointUuid],
            );
        }
    }

    /**
     * @param list<string> $servicePointUuids
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer
     */
    protected function createServicePointAddressCriteria(array $servicePointUuids): ServicePointAddressCriteriaTransfer
    {
        $servicePointAddressConditionsTransfer = (new ServicePointAddressConditionsTransfer())->setServicePointUuids($servicePointUuids);

        return (new ServicePointAddressCriteriaTransfer())
            ->setServicePointAddressConditions($servicePointAddressConditionsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointAddressTransfer>
     */
    protected function getServicePointAddressesIndexedByServicePointUuid(ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer): array
    {
        $servicePointAddressTransfersIndexedByServicePointUuid = [];
        foreach ($servicePointAddressCollectionTransfer->getServicePointAddresses() as $servicePointAddressTransfer) {
            $servicePointAddressTransfersIndexedByServicePointUuid[$servicePointAddressTransfer->getServicePointOrFail()->getUuidOrFail()] = $servicePointAddressTransfer;
        }

        return $servicePointAddressTransfersIndexedByServicePointUuid;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return list<string>
     */
    protected function extractServicePointUuidsFromGlueResourceTransfers(array $glueResourceTransfers): array
    {
        $servicePointUuids = [];
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            if (!$this->isApplicableResource($glueResourceTransfer)) {
                continue;
            }

            $servicePointUuids[] = $glueResourceTransfer->getIdOrFail();
        }

        return $servicePointUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return bool
     */
    protected function isApplicableResource(
        GlueResourceTransfer $glueResourceTransfer
    ): bool {
        return $glueResourceTransfer->getType() === ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     *
     * @return void
     */
    protected function addServicePointAddressesResourceRelationshipToGlueResourceTransfer(
        GlueResourceTransfer $glueResourceTransfer,
        ServicePointAddressTransfer $servicePointAddressTransfer
    ): void {
        $glueRelationshipTransfer = $this->servicePointAddressMapper
            ->mapServicePointAddressTransferToGlueRelationshipTransfer(
                $servicePointAddressTransfer,
                new GlueRelationshipTransfer(),
            );

        $glueResourceTransfer->addRelationship($glueRelationshipTransfer);
    }
}
