<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Business;

use Generated\Shared\Transfer\OrderInvoiceCollectionTransfer;
use Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer;
use Generated\Shared\Transfer\OrderInvoiceResponseTransfer;
use Generated\Shared\Transfer\OrderInvoiceSendRequestTransfer;
use Generated\Shared\Transfer\OrderInvoiceSendResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesInvoice\Business\SalesInvoiceBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceEntityManagerInterface getEntityManager()
 */
class SalesInvoiceFacade extends AbstractFacade implements SalesInvoiceFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceResponseTransfer
     */
    public function generateOrderInvoice(OrderTransfer $orderTransfer): OrderInvoiceResponseTransfer
    {
        return $this->getFactory()
            ->createOrderInvoiceWriter()
            ->generateOrderInvoice($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceCollectionTransfer
     */
    public function getOrderInvoices(OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer): OrderInvoiceCollectionTransfer
    {
        return $this->getFactory()
            ->createOrderInvoiceReader()
            ->getOrderInvoices($orderInvoiceCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderInvoiceSendRequestTransfer $orderInvoiceSendRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceSendResponseTransfer
     */
    public function sendOrderInvoices(OrderInvoiceSendRequestTransfer $orderInvoiceSendRequestTransfer): OrderInvoiceSendResponseTransfer
    {
        return $this->getFactory()
            ->createOrderInvoiceEmailSender()
            ->sendOrderInvoices($orderInvoiceSendRequestTransfer);
    }
}
