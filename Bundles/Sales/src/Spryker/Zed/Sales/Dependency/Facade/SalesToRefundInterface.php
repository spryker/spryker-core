<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Dependency\Facade;

use Generated\Shared\Transfer\RefundTransfer;

interface SalesToRefundInterface
{

    /**
     * @param int $idSalesOrder
     *
     * @return RefundTransfer[]
     */
    public function getRefundsByIdSalesOrder($idSalesOrder);

}
