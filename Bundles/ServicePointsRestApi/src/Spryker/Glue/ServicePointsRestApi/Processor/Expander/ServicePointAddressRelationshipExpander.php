<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Expander;

use Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointAddressReaderInterface;

class ServicePointAddressRelationshipExpander implements ServicePointAddressRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointAddressReaderInterface
     */
    protected ServicePointAddressReaderInterface $servicePointAddressReader;

    /**
     * @param \Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointAddressReaderInterface $servicePointAddressReader
     */
    public function __construct(ServicePointAddressReaderInterface $servicePointAddressReader)
    {
        $this->servicePointAddressReader = $servicePointAddressReader;
    }

    /**
     * @param list<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $restResources
     *
     * @return void
     */
    public function addServicePointAddressesResourceRelationshipsByServicePointUuid(array $restResources): void
    {
        $servicePointUuids = $this->extractServicePointUuidsFromRestResources($restResources);
        if (!count($servicePointUuids)) {
            return;
        }

        $servicePointAddressRestResourcesIndexedByServicePointUuid = $this->servicePointAddressReader
            ->getServicePointAddressRestResourcesIndexedByServicePointUuid($servicePointUuids);
        if (!count($servicePointAddressRestResourcesIndexedByServicePointUuid)) {
            return;
        }

        foreach ($restResources as $restResource) {
            $servicePointUuid = $restResource->getId();
            if (!$servicePointUuid || !isset($servicePointAddressRestResourcesIndexedByServicePointUuid[$servicePointUuid])) {
                continue;
            }

            $restResource->addRelationship(
                $servicePointAddressRestResourcesIndexedByServicePointUuid[$servicePointUuid],
            );
        }
    }

    /**
     * @param list<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $restResources
     *
     * @return list<string>
     */
    protected function extractServicePointUuidsFromRestResources(array $restResources): array
    {
        $servicePointUuids = [];

        foreach ($restResources as $restResource) {
            if ($restResource->getId()) {
                $servicePointUuids[] = $restResource->getId();
            }
        }

        return $servicePointUuids;
    }
}
