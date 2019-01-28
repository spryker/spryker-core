<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface SalesReclamationToSalesFacadeInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveSalesOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function updateOrder(OrderTransfer $orderTransfer, $idSalesOrder);
}
