<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business;

use Generated\Shared\Transfer\EventPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesPaymentCollectionTransfer;
use Generated\Shared\Transfer\SalesPaymentCriteriaTransfer;
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

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendEventPaymentConfirmationPending(EventPaymentTransfer $eventPaymentTransfer): void
    {
        $this->getFactory()
            ->createMessageEmitter()
            ->sendCapturePaymentMessage($eventPaymentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendEventPaymentRefundPending(EventPaymentTransfer $eventPaymentTransfer): void
    {
        $this->getFactory()
            ->createMessageEmitter()
            ->sendRefundPaymentMessage($eventPaymentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendEventPaymentCancelReservationPending(EventPaymentTransfer $eventPaymentTransfer): void
    {
        $this->getFactory()
            ->createMessageEmitter()
            ->sendCancelPaymentMessage($eventPaymentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendCapturePaymentMessage(EventPaymentTransfer $eventPaymentTransfer): void
    {
        $this->getFactory()
            ->createMessageEmitter()
            ->sendCapturePaymentMessage($eventPaymentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendCancelPaymentMessage(EventPaymentTransfer $eventPaymentTransfer): void
    {
        $this->getFactory()
            ->createMessageEmitter()
            ->sendCancelPaymentMessage($eventPaymentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendRefundPaymentMessage(EventPaymentTransfer $eventPaymentTransfer): void
    {
        $this->getFactory()
            ->createMessageEmitter()
            ->sendRefundPaymentMessage($eventPaymentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesPaymentCriteriaTransfer $salesPaymentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentCollectionTransfer
     */
    public function getSalesPaymentCollection(SalesPaymentCriteriaTransfer $salesPaymentCriteriaTransfer): SalesPaymentCollectionTransfer
    {
        return $this->getFactory()
            ->createSalesPaymentReader()
            ->getSalesPaymentCollection($salesPaymentCriteriaTransfer);
    }

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
    public function replaceSalesPayments(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->getFactory()
            ->createSalesPaymentReplacer()
            ->replaceSalesPayments($quoteTransfer, $saveOrderTransfer);
    }
}
