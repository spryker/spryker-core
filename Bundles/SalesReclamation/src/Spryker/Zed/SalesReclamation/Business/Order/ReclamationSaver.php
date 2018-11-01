<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Order;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\SalesReclamation\Persistence\SalesReclamationQueryContainerInterface;

class ReclamationSaver implements ReclamationSaverInterface
{
    /**
     * @var \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationQueryContainerInterface $queryContainer
     */
    public function __construct(
        SalesReclamationQueryContainerInterface $queryContainer
    ) {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        if (!$quoteTransfer->getReclamationId()) {
            return;
        }

        $saveOrderTransfer->requireIdSalesOrder();

        $spySalesOrder = $this->queryContainer
            ->querySalesOrderById($saveOrderTransfer->getIdSalesOrder())
            ->findOne();

        $spySalesOrder->setFkSalesReclamation($quoteTransfer->getReclamationId());
        $spySalesOrder->save();
    }
}
