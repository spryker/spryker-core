<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Grouper;

interface ShipmentTypeGrouperInterface
{
    /**
     * @param array<int, list<int>> $productShipmentTypeIds
     * @param list<\Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return array<int, list<\Generated\Shared\Transfer\ShipmentTypeTransfer>>
     */
    public function groupShipmentTypesByIdProductConcrete(
        array $productShipmentTypeIds,
        array $shipmentTypeTransfers
    ): array;
}
