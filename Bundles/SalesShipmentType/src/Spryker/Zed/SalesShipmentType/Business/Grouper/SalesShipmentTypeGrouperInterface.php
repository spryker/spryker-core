<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Business\Grouper;

interface SalesShipmentTypeGrouperInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer> $salesShipmentTypeTransfers
     *
     * @return array<string, list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer>>
     */
    public function getSalesShipmentTypeTransfersGroupedByKey(array $salesShipmentTypeTransfers): array;

    /**
     * @param list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer> $salesShipmentTypeTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\SalesShipmentTypeTransfer>
     */
    public function getSalesShipmentTypeTransfersIndexedByName(array $salesShipmentTypeTransfers): array;
}
