<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;

interface PayolutionToSalesInterface
{

    /**
     * @param int $idSalesOrder
     *
     * @return OrderTransfer
     */
    public function getOrderTotalsByIdSalesOrder($idSalesOrder);

}
