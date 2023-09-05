<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Reader;

interface ServiceTypeReaderInterface
{
    /**
     * @param list<string> $serviceTypeUuids
     *
     * @return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    public function getServiceTypeRestResourcesIndexedByServiceTypeUuid(array $serviceTypeUuids): array;
}
