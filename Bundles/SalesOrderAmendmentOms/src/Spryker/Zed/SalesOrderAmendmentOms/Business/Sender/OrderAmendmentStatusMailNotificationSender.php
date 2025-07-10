<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Sender;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Mapper\OrderMapperInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\SalesOrderAmendmentQuoteReaderInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToMailFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface;

class OrderAmendmentStatusMailNotificationSender implements OrderAmendmentStatusMailNotificationSenderInterface
{
    /**
     * @uses \Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Mail\NotifyOrderAmendmentAppliedMailTypeBuilderPlugin::MAIL_TYPE
     *
     * @var string
     */
    protected const MAIL_TYPE_ORDER_AMENDMENT_APPLIED = 'notify order amendment applied';

    /**
     * @uses \Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Mail\NotifyOrderAmendmentFailedMailTypeBuilderPlugin::MAIL_TYPE
     *
     * @var string
     */
    protected const MAIL_TYPE_ORDER_AMENDMENT_FAILED = 'notify order amendment failed';

    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToMailFacadeInterface $mailFacade
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\SalesOrderAmendmentQuoteReaderInterface $salesOrderAmendmentQuoteReader
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacade
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Business\Mapper\OrderMapperInterface $orderMapper
     */
    public function __construct(
        protected SalesOrderAmendmentOmsToMailFacadeInterface $mailFacade,
        protected SalesOrderAmendmentQuoteReaderInterface $salesOrderAmendmentQuoteReader,
        protected SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacade,
        protected OrderMapperInterface $orderMapper
    ) {
    }

    /**
     * @param string $orderReference
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function notifyOrderAmendmentApplied(string $orderReference, int $idSalesOrder): void
    {
        $this->handleNotification($orderReference, $idSalesOrder, static::MAIL_TYPE_ORDER_AMENDMENT_APPLIED);
    }

    /**
     * @param string $orderReference
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function notifyOrderAmendmentFailed(string $orderReference, int $idSalesOrder): void
    {
        $this->handleNotification($orderReference, $idSalesOrder, static::MAIL_TYPE_ORDER_AMENDMENT_FAILED);
    }

    /**
     * @param string $orderReference
     * @param int $idSalesOrder
     * @param string $mailType
     *
     * @return void
     */
    protected function handleNotification(string $orderReference, int $idSalesOrder, string $mailType): void
    {
        $salesOrderAmendmentQuoteTransfer = $this->salesOrderAmendmentQuoteReader
            ->findSalesOrderAmendmentQuoteByOrderReference($orderReference, true);

        if (!$salesOrderAmendmentQuoteTransfer) {
            return;
        }

        $quoteTransfer = $salesOrderAmendmentQuoteTransfer->getQuoteOrFail();

        $orderTransfer = $this->orderMapper->mapQuoteTransferToOrderTransfer($quoteTransfer, new OrderTransfer());
        $orderTransfer->setIdSalesOrder($idSalesOrder)->setOrderReference($orderReference);

        $mailTransfer = (new MailTransfer())->fromArray($salesOrderAmendmentQuoteTransfer->toArray(), true);
        $mailTransfer = $mailTransfer
            ->setType($mailType)
            ->setQuote($quoteTransfer)
            ->setOrder($orderTransfer)
            ->setLocale($orderTransfer->getLocale())
            ->setStoreName($orderTransfer->getStore());

        $this->mailFacade->handleMail($mailTransfer);
    }
}
