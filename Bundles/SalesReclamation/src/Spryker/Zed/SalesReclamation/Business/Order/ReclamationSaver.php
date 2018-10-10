<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Order;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface;

class ReclamationSaver implements ReclamationSaverInterface
{
    /**
     * @var \Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        SalesReclamationToSalesFacadeInterface $salesFacade
    ) {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        if (!$quoteTransfer->getReclamationId()) {
            return;
        }

        $orderTransfer = $this->salesFacade->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $orderTransfer->setFkSalesReclamation($quoteTransfer->getReclamationId());

        $this->salesFacade->updateOrder($orderTransfer, $saveOrderTransfer->getIdSalesOrder());
    }
}
