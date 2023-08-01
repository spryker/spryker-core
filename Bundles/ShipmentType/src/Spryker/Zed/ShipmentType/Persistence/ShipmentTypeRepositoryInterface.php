<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Persistence;

use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;

interface ShipmentTypeRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function getShipmentTypeCollection(
        ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
    ): ShipmentTypeCollectionTransfer;

    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return array<int, \Generated\Shared\Transfer\StoreRelationTransfer>
     */
    public function getShipmentTypeStoreRelationsIndexedByIdShipmentType(array $shipmentTypeIds): array;

    /**
     * @param list<int> $shipmentMethodIds
     *
     * @return array<int, list<int>>
     */
    public function getShipmentMethodIdsGroupedByIdShipmentType(array $shipmentMethodIds): array;

    /**
     * @param list<string> $shipmentTypeUuids
     * @param string $storeName
     *
     * @return list<int>
     */
    public function getShipmentMethodIdsByShipmentTypeConditions(array $shipmentTypeUuids, string $storeName): array;
}
