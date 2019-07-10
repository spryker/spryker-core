<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentExpense;

use ArrayObject;

interface ShipmentExpenseCollectionRemoverInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     * @param string $shipmentHash
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[]
     */
    public function removeExpenseByShipmentHash(ArrayObject $expenseTransfers, string $shipmentHash): ArrayObject;
}
