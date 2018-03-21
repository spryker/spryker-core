<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation;

interface SalesReclamationFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int[] ...$idsOrderItem
     *
     * @return null|\Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation
     */
    public function createReclamation(OrderTransfer $orderTransfer, int ... $idsOrderItem): ?SpySalesReclamation;
}
