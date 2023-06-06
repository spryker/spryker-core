<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeStorage\Generator;

interface ShipmentTypeStorageKeyGeneratorInterface
{
    /**
     * @param list<int> $shipmentTypeIds
     * @param string $storeName
     *
     * @return list<string>
     */
    public function generateShipmentTypeStorageKeys(array $shipmentTypeIds, string $storeName): array;

    /**
     * @param list<string> $shipmentTypeUuids
     * @param string $storeName
     *
     * @return list<string>
     */
    public function generateShipmentTypeStorageUuidMappingKeys(array $shipmentTypeUuids, string $storeName): array;
}
