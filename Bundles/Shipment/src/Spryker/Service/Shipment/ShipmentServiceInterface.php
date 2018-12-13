<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Service\Shipment;

interface ShipmentServiceInterface
{
    /**
     * Specification:
     * - Iterates all items grouping them by shipment.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransferTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShipment(array $items): array;
}
