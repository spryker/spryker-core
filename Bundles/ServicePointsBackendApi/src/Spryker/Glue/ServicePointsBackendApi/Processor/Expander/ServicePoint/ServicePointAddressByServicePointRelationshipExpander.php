<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Expander\ServicePoint;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionTransfer;
use Generated\Shared\Transfer\ServicePointAddressConditionsTransfer;
use Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Extractor\GlueResourceExtractorInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Filter\GlueResourceFilterInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServicePointAddressByServicePointRelationshipExpander implements ServicePointAddressByServicePointRelationshipExpanderInterface
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
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Filter\GlueResourceFilterInterface
     */
    protected GlueResourceFilterInterface $glueResourceFilter;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Extractor\GlueResourceExtractorInterface
     */
    protected GlueResourceExtractorInterface $glueResourceExtractor;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapperInterface $servicePointAddressMapper
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Filter\GlueResourceFilterInterface $glueResourceFilter
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Extractor\GlueResourceExtractorInterface $glueResourceExtractor
     */
    public function __construct(
        ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade,
        ServicePointAddressMapperInterface $servicePointAddressMapper,
        GlueResourceFilterInterface $glueResourceFilter,
        GlueResourceExtractorInterface $glueResourceExtractor
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->servicePointAddressMapper = $servicePointAddressMapper;
        $this->glueResourceFilter = $glueResourceFilter;
        $this->glueResourceExtractor = $glueResourceExtractor;
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
        $servicePointGlueResourceTransfers = $this->glueResourceFilter
            ->filterGlueResourcesByType(
                $glueResourceTransfers,
                ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS,
            );
        $servicePointUuids = $this->glueResourceExtractor
            ->extractUuidsFromGlueResourceTransfers($servicePointGlueResourceTransfers);
        $servicePointAddressCollectionTransfer = $this->servicePointFacade->getServicePointAddressCollection(
            $this->createServicePointAddressCriteria($servicePointUuids),
        );

        $servicePointAddressTransfersIndexedByServicePointUuid = $this->getServicePointAddressesIndexedByServicePointUuid($servicePointAddressCollectionTransfer);

        foreach ($servicePointGlueResourceTransfers as $glueResourceTransfer) {
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
