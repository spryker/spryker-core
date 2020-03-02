<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence;

use Generated\Shared\Transfer\OrderTransfer;

interface CompanyBusinessUnitSalesConnectorEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function updateOrder(OrderTransfer $orderTransfer): void;
}
