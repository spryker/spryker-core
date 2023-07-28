<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePoint\Persistence;

interface ShipmentTypeServicePointRepositoryInterface
{
    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return array<int, int>
     */
    public function getServiceTypeIdsIndexedByIdShipmentType(array $shipmentTypeIds): array;
}
