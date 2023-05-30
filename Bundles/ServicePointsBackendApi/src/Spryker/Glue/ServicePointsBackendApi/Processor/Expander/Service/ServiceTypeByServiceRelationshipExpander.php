<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Expander\Service;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\ServicePointsBackendApi\Processor\Extractor\GlueResourceExtractorInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Filter\GlueResourceFilterInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServiceTypeByServiceResourceRelationshipReaderInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServiceTypeByServiceRelationshipExpander implements ServiceTypeByServiceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServiceTypeByServiceResourceRelationshipReaderInterface
     */
    protected ServiceTypeByServiceResourceRelationshipReaderInterface $serviceTypeByServiceResourceRelationshipReader;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Filter\GlueResourceFilterInterface
     */
    protected GlueResourceFilterInterface $glueResourceFilter;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Extractor\GlueResourceExtractorInterface
     */
    protected GlueResourceExtractorInterface $serviceGlueResourceExtractor;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServiceTypeByServiceResourceRelationshipReaderInterface $serviceTypeByServiceResourceRelationshipReader
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Filter\GlueResourceFilterInterface $glueResourceFilter
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Extractor\GlueResourceExtractorInterface $serviceGlueResourceExtractor
     */
    public function __construct(
        ServiceTypeByServiceResourceRelationshipReaderInterface $serviceTypeByServiceResourceRelationshipReader,
        GlueResourceFilterInterface $glueResourceFilter,
        GlueResourceExtractorInterface $serviceGlueResourceExtractor
    ) {
        $this->serviceTypeByServiceResourceRelationshipReader = $serviceTypeByServiceResourceRelationshipReader;
        $this->glueResourceFilter = $glueResourceFilter;
        $this->serviceGlueResourceExtractor = $serviceGlueResourceExtractor;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addServiceTypesResourceRelationships(array $glueResourceTransfers, GlueRequestTransfer $glueRequestTransfer): void
    {
        $serviceGlueResourceTransfers = $this->glueResourceFilter
            ->filterGlueResourcesByType($glueResourceTransfers, ServicePointsBackendApiConfig::RESOURCE_SERVICES);
        $serviceUuids = $this->serviceGlueResourceExtractor
            ->extractUuidsFromGlueResourceTransfers($serviceGlueResourceTransfers);
        $serviceTypeRelationshipTransfersIndexedByServiceUuids = $this->serviceTypeByServiceResourceRelationshipReader
            ->getServiceTypeRelationshipsIndexedByServiceUuids($serviceUuids);

        $this->addServiceTypeRelationshipsToGlueResources(
            $serviceGlueResourceTransfers,
            $serviceTypeRelationshipTransfersIndexedByServiceUuids,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer> $serviceTypeRelationshipTransfersIndexedByServiceUuid
     *
     * @return void
     */
    protected function addServiceTypeRelationshipsToGlueResources(
        array $glueResourceTransfers,
        array $serviceTypeRelationshipTransfersIndexedByServiceUuid
    ): void {
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer|null $serviceTypeRelationshipTransfer */
            $serviceTypeRelationshipTransfer = $serviceTypeRelationshipTransfersIndexedByServiceUuid[$glueResourceTransfer->getIdOrFail()] ?? null;
            if ($serviceTypeRelationshipTransfer === null) {
                continue;
            }

            $glueResourceTransfer->addRelationship($serviceTypeRelationshipTransfer);
        }
    }
}
