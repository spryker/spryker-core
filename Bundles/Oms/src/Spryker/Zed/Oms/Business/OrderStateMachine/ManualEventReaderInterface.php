<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

interface ManualEventReaderInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return string[]
     */
    public function getGroupedDistinctManualEventsByIdSalesOrder(int $idSalesOrder): array;
}
