<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Business\EmailSender;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderInvoiceCollectionTransfer;
use Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer;
use Generated\Shared\Transfer\OrderInvoiceSendRequestTransfer;
use Generated\Shared\Transfer\OrderInvoiceSendResponseTransfer;
use Generated\Shared\Transfer\OrderInvoiceTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesInvoice\Business\Reader\OrderInvoiceReaderInterface;
use Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToMailFacadeInterface;
use Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSalesFacadeInterface;
use Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceEntityManagerInterface;
use Spryker\Zed\SalesInvoice\SalesInvoiceConfig;

class OrderInvoiceEmailSender implements OrderInvoiceEmailSenderInterface
{
    /**
     * @var \Spryker\Zed\SalesInvoice\Business\Reader\OrderInvoiceReaderInterface
     */
    protected $orderInvoiceReader;

    /**
     * @var \Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToMailFacadeInterface
     */
    protected $mailFacade;

    /**
     * @var \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\SalesInvoice\Business\Renderer\OrderInvoiceRendererInterface
     */
    protected $orderInvoiceRenderer;

    /**
     * @var \Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceEntityManagerInterface $entityManager
     * @param \Spryker\Zed\SalesInvoice\Business\Reader\OrderInvoiceReaderInterface $orderInvoiceReader
     * @param \Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToMailFacadeInterface $mailFacade
     */
    public function __construct(
        SalesInvoiceEntityManagerInterface $entityManager,
        OrderInvoiceReaderInterface $orderInvoiceReader,
        SalesInvoiceToSalesFacadeInterface $salesFacade,
        SalesInvoiceToMailFacadeInterface $mailFacade
    ) {
        $this->orderInvoiceReader = $orderInvoiceReader;
        $this->entityManager = $entityManager;
        $this->mailFacade = $mailFacade;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceSendRequestTransfer $orderInvoiceSendRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceSendResponseTransfer
     */
    public function sendOrderInvoices(OrderInvoiceSendRequestTransfer $orderInvoiceSendRequestTransfer): OrderInvoiceSendResponseTransfer
    {
        $orderInvoiceCollectionTransfer = $this->getOrderInvoices($orderInvoiceSendRequestTransfer);

        $orderInvoiceIds = [];
        foreach ($orderInvoiceCollectionTransfer->getOrderInvoices() as $orderInvoiceTransfer) {
            $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($orderInvoiceTransfer->getIdSalesOrder());
            $this->orderInvoiceReader->expandOrderInvoiceWithRenderedInvoice($orderInvoiceTransfer, $orderTransfer);
            $this->handleMail($orderInvoiceTransfer, $orderTransfer);
            $orderInvoiceIds[] = $orderInvoiceTransfer->getIdSalesOrderInvoice();
        }

        $this->entityManager->markOrderInvoicesAsEmailSent($orderInvoiceIds);

        return (new OrderInvoiceSendResponseTransfer())
            ->setCount(
                $orderInvoiceCollectionTransfer->getOrderInvoices()->count()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceSendRequestTransfer $orderInvoiceSendRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceCollectionTransfer
     */
    protected function getOrderInvoices(OrderInvoiceSendRequestTransfer $orderInvoiceSendRequestTransfer): OrderInvoiceCollectionTransfer
    {
        $orderInvoiceCriteriaTransfer = (new OrderInvoiceCriteriaTransfer())
            ->setSalesOrderIds($orderInvoiceSendRequestTransfer->getSalesOrderIds())
            ->setExpandWithRenderedInvoice(false);

        if (!$orderInvoiceSendRequestTransfer->getForce()) {
            $orderInvoiceCriteriaTransfer->setIsEmailSent(false);
        }

        $orderInvoiceCriteriaTransfer->setFilter(
            (new FilterTransfer())
                ->setLimit($orderInvoiceSendRequestTransfer->requireBatch()->getBatch())
                ->setOffset(0)
        );

        return $this->orderInvoiceReader->getOrderInvoices($orderInvoiceCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceTransfer $orderInvoiceTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function handleMail(OrderInvoiceTransfer $orderInvoiceTransfer, OrderTransfer $orderTransfer): void
    {
        $mailTransfer = (new MailTransfer())
            ->setType(SalesInvoiceConfig::ORDER_INVOICE_MAIL_TYPE)
            ->setOrderInvoice($orderInvoiceTransfer)
            ->setOrder($orderTransfer)
            ->setLocale($orderTransfer->getLocale());

        $this->mailFacade->handleMail($mailTransfer);
    }
}
