<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Expander\Service;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\ServicePointsBackendApi\Processor\Extractor\GlueResourceExtractorInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Filter\GlueResourceFilterInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServicePointByServiceResourceRelationshipReaderInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServicePointByServiceRelationshipExpander implements ServicePointByServiceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServicePointByServiceResourceRelationshipReaderInterface
     */
    protected ServicePointByServiceResourceRelationshipReaderInterface $servicePointByServiceResourceRelationshipReader;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Filter\GlueResourceFilterInterface
     */
    protected GlueResourceFilterInterface $glueResourceFilter;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Extractor\GlueResourceExtractorInterface
     */
    protected GlueResourceExtractorInterface $serviceGlueResourceExtractor;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServicePointByServiceResourceRelationshipReaderInterface $servicePointByServiceResourceRelationshipReader
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Filter\GlueResourceFilterInterface $glueResourceFilter
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Extractor\GlueResourceExtractorInterface $serviceGlueResourceExtractor
     */
    public function __construct(
        ServicePointByServiceResourceRelationshipReaderInterface $servicePointByServiceResourceRelationshipReader,
        GlueResourceFilterInterface $glueResourceFilter,
        GlueResourceExtractorInterface $serviceGlueResourceExtractor
    ) {
        $this->servicePointByServiceResourceRelationshipReader = $servicePointByServiceResourceRelationshipReader;
        $this->glueResourceFilter = $glueResourceFilter;
        $this->serviceGlueResourceExtractor = $serviceGlueResourceExtractor;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addServicePointsResourceRelationships(array $glueResourceTransfers, GlueRequestTransfer $glueRequestTransfer): void
    {
        $serviceGlueResourceTransfers = $this->glueResourceFilter
            ->filterGlueResourcesByType($glueResourceTransfers, ServicePointsBackendApiConfig::RESOURCE_SERVICES);
        $serviceUuids = $this->serviceGlueResourceExtractor
            ->extractUuidsFromGlueResourceTransfers($serviceGlueResourceTransfers);
        $servicePointRelationshipTransfersIndexedByServiceUuids = $this->servicePointByServiceResourceRelationshipReader
            ->getServicePointRelationshipsIndexedByServiceUuids($serviceUuids);

        $this->addServicePointRelationshipsToGlueResources(
            $serviceGlueResourceTransfers,
            $servicePointRelationshipTransfersIndexedByServiceUuids,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer> $servicePointRelationshipTransfersIndexedByServiceUuid
     *
     * @return void
     */
    protected function addServicePointRelationshipsToGlueResources(
        array $glueResourceTransfers,
        array $servicePointRelationshipTransfersIndexedByServiceUuid
    ): void {
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer|null $servicePointRelationshipTransfer */
            $servicePointRelationshipTransfer = $servicePointRelationshipTransfersIndexedByServiceUuid[$glueResourceTransfer->getIdOrFail()] ?? null;
            if ($servicePointRelationshipTransfer === null) {
                continue;
            }

            $glueResourceTransfer->addRelationship($servicePointRelationshipTransfer);
        }
    }
}
