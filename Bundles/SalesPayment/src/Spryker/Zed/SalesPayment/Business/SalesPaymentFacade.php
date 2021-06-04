<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesPayment\Business\SalesPaymentBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesPayment\Persistence\SalesPaymentRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesPayment\Persistence\SalesPaymentEntityManagerInterface getEntityManager()
 */
class SalesPaymentFacade extends AbstractFacade implements SalesPaymentFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayments(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->getFactory()
            ->createSalesPaymentWriter()
            ->saveOrderPayments($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithPayments(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()
            ->createSalesOrderExpander()
            ->expandOrderWithPayments($orderTransfer);
    }
}
