<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Communication\Controller;

use Generated\Shared\Transfer\OrderInvoiceCollectionTransfer;
use Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\SalesInvoice\Business\SalesInvoiceBusinessFactory;

/**
 * @method \Spryker\Zed\SalesInvoice\Communication\SalesInvoiceCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesInvoice\Business\SalesInvoiceFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface getRepository()
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
            ->setSalesOrderIds([2])
            ->setExpandWithRenderedInvoice(true);

        return (new SalesInvoiceBusinessFactory())->createOrderInvoiceReader()->getOrderInvoices($orderInvoiceCriteriaTransfer);
    }
}
