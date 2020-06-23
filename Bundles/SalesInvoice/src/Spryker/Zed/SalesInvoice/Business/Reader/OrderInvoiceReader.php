<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Business\Reader;

use Generated\Shared\Transfer\OrderInvoiceCollectionTransfer;
use Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer;
use Spryker\Zed\SalesInvoice\Business\Renderer\OrderInvoiceRendererInterface;
use Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSalesFacadeInterface;
use Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface;

class OrderInvoiceReader implements OrderInvoiceReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\SalesInvoice\Business\Renderer\OrderInvoiceRendererInterface
     */
    protected $orderInvoiceRenderer;

    /**
     * @var \Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface $repository
     * @param \Spryker\Zed\SalesInvoice\Business\Renderer\OrderInvoiceRendererInterface $orderInvoiceRenderer
     * @param \Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        SalesInvoiceRepositoryInterface $repository,
        OrderInvoiceRendererInterface $orderInvoiceRenderer,
        SalesInvoiceToSalesFacadeInterface $salesFacade
    ) {
        $this->repository = $repository;
        $this->orderInvoiceRenderer = $orderInvoiceRenderer;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceCollectionTransfer
     */
    public function getOrderInvoices(OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer): OrderInvoiceCollectionTransfer
    {
        $orderInvoiceCollectionTransfer = $this->repository->getOrderInvoices($orderInvoiceCriteriaTransfer);

        if (!count($orderInvoiceCollectionTransfer->getOrderInvoices())) {
            return $orderInvoiceCollectionTransfer;
        }

        return $this->expandWithRenderedInvoice($orderInvoiceCriteriaTransfer, $orderInvoiceCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer
     * @param \Generated\Shared\Transfer\OrderInvoiceCollectionTransfer $orderInvoiceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceCollectionTransfer
     */
    protected function expandWithRenderedInvoice(
        OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer,
        OrderInvoiceCollectionTransfer $orderInvoiceCollectionTransfer
    ): OrderInvoiceCollectionTransfer {
        if (!$orderInvoiceCriteriaTransfer->getExpandWithRenderedInvoice()) {
            return $orderInvoiceCollectionTransfer;
        }

        foreach ($orderInvoiceCollectionTransfer->getOrderInvoices() as $orderInvoiceTransfer) {
            $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($orderInvoiceTransfer->getIdSalesOrder());
            $orderInvoiceTransfer->setRenderedInvoice(
                $this->orderInvoiceRenderer->renderOrderInvoice($orderInvoiceTransfer, $orderTransfer)
            );
        }

        return $orderInvoiceCollectionTransfer;
    }
}
