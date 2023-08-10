<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Business\Grouper;

class SalesShipmentTypeGrouper implements SalesShipmentTypeGrouperInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer> $salesShipmentTypeTransfers
     *
     * @return array<string, list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer>>
     */
    public function getSalesShipmentTypeTransfersGroupedByKey(array $salesShipmentTypeTransfers): array
    {
        $groupedSalesShipmentTypeTransfers = [];
        foreach ($salesShipmentTypeTransfers as $salesShipmentTypeTransfer) {
            $groupedSalesShipmentTypeTransfers[$salesShipmentTypeTransfer->getKeyOrFail()][] = $salesShipmentTypeTransfer;
        }

        return $groupedSalesShipmentTypeTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer> $salesShipmentTypeTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\SalesShipmentTypeTransfer>
     */
    public function getSalesShipmentTypeTransfersIndexedByName(array $salesShipmentTypeTransfers): array
    {
        $indexedSalesShipmentTypeTransfers = [];
        foreach ($salesShipmentTypeTransfers as $salesShipmentTypeTransfer) {
            $indexedSalesShipmentTypeTransfers[$salesShipmentTypeTransfer->getNameOrFail()] = $salesShipmentTypeTransfer;
        }

        return $indexedSalesShipmentTypeTransfers;
    }
}
