<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Communication\Controller;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\OrderInvoiceCollectionTransfer;
use Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer;
use Generated\Shared\Transfer\OrderInvoiceSendRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\SalesInvoice\Communication\SalesInvoiceCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesInvoice\Business\SalesInvoiceFacadeInterface getFacade()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $orderInvoiceCollectionTransfer = $this->getOrderInvoices();

        return $this->viewResponse(['invoices' => $orderInvoiceCollectionTransfer->getOrderInvoices()]);
    }



    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceSendRequestTransfer $orderInvoiceSendRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceCollectionTransfer
     */
    protected function getOrderInvoices(): OrderInvoiceCollectionTransfer
    {
        $orderInvoiceCriteriaTransfer = (new OrderInvoiceCriteriaTransfer())
            ->setSalesOrderIds()
            ->setExpandWithRenderedInvoice(true);

        return (new \Spryker\Zed\SalesInvoice\Business\SalesInvoiceBusinessFactory())->createOrderInvoiceReader()->getOrderInvoices($orderInvoiceCriteriaTransfer);
    }
}
