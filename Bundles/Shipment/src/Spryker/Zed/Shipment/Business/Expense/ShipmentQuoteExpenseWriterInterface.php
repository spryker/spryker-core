<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Expense;

use Generated\Shared\Transfer\CalculableObjectTransfer;

interface ShipmentQuoteExpenseWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function removeObsoleteShipmentExpenses(CalculableObjectTransfer $calculableObjectTransfer): void;
}
