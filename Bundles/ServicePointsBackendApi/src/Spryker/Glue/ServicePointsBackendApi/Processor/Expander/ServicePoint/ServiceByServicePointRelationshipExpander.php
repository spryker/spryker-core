<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Expander\ServicePoint;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Extractor\GlueResourceExtractorInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Filter\GlueResourceFilterInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServiceByServicePointRelationshipExpander implements ServiceByServicePointRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface
     */
    protected ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceMapperInterface
     */
    protected ServiceMapperInterface $serviceMapper;

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
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceMapperInterface $serviceMapper
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Filter\GlueResourceFilterInterface $glueResourceFilter
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Extractor\GlueResourceExtractorInterface $glueResourceExtractor
     */
    public function __construct(
        ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade,
        ServiceMapperInterface $serviceMapper,
        GlueResourceFilterInterface $glueResourceFilter,
        GlueResourceExtractorInterface $glueResourceExtractor
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->serviceMapper = $serviceMapper;
        $this->glueResourceFilter = $glueResourceFilter;
        $this->glueResourceExtractor = $glueResourceExtractor;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addServicesResourceRelationships(
        array $glueResourceTransfers,
        GlueRequestTransfer $glueRequestTransfer
    ): void {
        $servicePointGlueResourceTransfers = $this->glueResourceFilter
            ->filterGlueResourcesByType($glueResourceTransfers, ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS);

        $servicePointUuids = $this->glueResourceExtractor->extractUuidsFromGlueResourceTransfers($servicePointGlueResourceTransfers);
        $serviceCollectionTransfer = $this->servicePointFacade->getServiceCollection(
            $this->createServiceCriteria($servicePointUuids),
        );

        $serviceTransfersGroupedByServicePointUuid = $this->getServicesGroupedByServicePointUuid($serviceCollectionTransfer);

        foreach ($servicePointGlueResourceTransfers as $glueResourceTransfer) {
            $servicePointUuid = $glueResourceTransfer->getIdOrFail();

            if (!isset($serviceTransfersGroupedByServicePointUuid[$servicePointUuid])) {
                continue;
            }

            $this->addServicesResourceRelationshipToGlueResource(
                $glueResourceTransfer,
                $serviceTransfersGroupedByServicePointUuid[$servicePointUuid],
            );
        }
    }

    /**
     * @param list<string> $servicePointUuids
     *
     * @return \Generated\Shared\Transfer\ServiceCriteriaTransfer
     */
    protected function createServiceCriteria(array $servicePointUuids): ServiceCriteriaTransfer
    {
        $serviceConditionsTransfer = (new ServiceConditionsTransfer())->setServicePointUuids($servicePointUuids);

        return (new ServiceCriteriaTransfer())
            ->setServiceConditions($serviceConditionsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionTransfer $serviceCollectionTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ServiceTransfer>>
     */
    protected function getServicesGroupedByServicePointUuid(ServiceCollectionTransfer $serviceCollectionTransfer): array
    {
        $serviceTransfersGroupedByServicePointUuid = [];
        foreach ($serviceCollectionTransfer->getServices() as $serviceTransfer) {
            $serviceTransfersGroupedByServicePointUuid[$serviceTransfer->getServicePointOrFail()->getUuidOrFail()][] = $serviceTransfer;
        }

        return $serviceTransfersGroupedByServicePointUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     * @param list<\Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers
     *
     * @return void
     */
    protected function addServicesResourceRelationshipToGlueResource(
        GlueResourceTransfer $glueResourceTransfer,
        array $serviceTransfers
    ): void {
        foreach ($serviceTransfers as $serviceTransfer) {
            $glueRelationshipTransfer = $this->serviceMapper
                ->mapServiceTransferToGlueRelationshipTransfer(
                    $serviceTransfer,
                    new GlueRelationshipTransfer(),
                );

            $glueResourceTransfer->addRelationship($glueRelationshipTransfer);
        }
    }
}
