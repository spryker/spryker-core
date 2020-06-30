<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Business\Reader;

use Generated\Shared\Transfer\OrderInvoiceCollectionTransfer;
use Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer;
use Generated\Shared\Transfer\OrderInvoiceTransfer;
use Generated\Shared\Transfer\OrderTransfer;
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
     * @var \Spryker\Zed\SalesInvoiceExtension\Dependency\Plugin\OrderInvoicesExpanderPluginInterface[]
     */
    protected $orderInvoicesExpanderPlugins;

    /**
     * @param \Spryker\Zed\SalesInvoice\Persistence\SalesInvoiceRepositoryInterface $repository
     * @param \Spryker\Zed\SalesInvoice\Business\Renderer\OrderInvoiceRendererInterface $orderInvoiceRenderer
     * @param \Spryker\Zed\SalesInvoice\Dependency\Facade\SalesInvoiceToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\SalesInvoiceExtension\Dependency\Plugin\OrderInvoicesExpanderPluginInterface[] $orderInvoicesExpanderPlugins
     */
    public function __construct(
        SalesInvoiceRepositoryInterface $repository,
        OrderInvoiceRendererInterface $orderInvoiceRenderer,
        SalesInvoiceToSalesFacadeInterface $salesFacade,
        array $orderInvoicesExpanderPlugins
    ) {
        $this->repository = $repository;
        $this->orderInvoiceRenderer = $orderInvoiceRenderer;
        $this->salesFacade = $salesFacade;
        $this->orderInvoicesExpanderPlugins = $orderInvoicesExpanderPlugins;
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

        $orderInvoiceCollectionTransfer = $this->executeOrderInvoicesExpanderPlugins($orderInvoiceCollectionTransfer);

        return $this->expandOrderInvoiceCollectionWithRenderedInvoice($orderInvoiceCriteriaTransfer, $orderInvoiceCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceTransfer $orderInvoiceTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceTransfer
     */
    public function expandOrderInvoiceWithRenderedInvoice(
        OrderInvoiceTransfer $orderInvoiceTransfer,
        OrderTransfer $orderTransfer
    ): OrderInvoiceTransfer {
        return $orderInvoiceTransfer->setRenderedInvoice(
            $this->orderInvoiceRenderer->renderOrderInvoice($orderInvoiceTransfer, $orderTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer
     * @param \Generated\Shared\Transfer\OrderInvoiceCollectionTransfer $orderInvoiceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceCollectionTransfer
     */
    protected function expandOrderInvoiceCollectionWithRenderedInvoice(
        OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer,
        OrderInvoiceCollectionTransfer $orderInvoiceCollectionTransfer
    ): OrderInvoiceCollectionTransfer {
        if (!$orderInvoiceCriteriaTransfer->getExpandWithRenderedInvoice()) {
            return $orderInvoiceCollectionTransfer;
        }

        foreach ($orderInvoiceCollectionTransfer->getOrderInvoices() as $orderInvoiceTransfer) {
            $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($orderInvoiceTransfer->getIdSalesOrder());
            $this->expandOrderInvoiceWithRenderedInvoice($orderInvoiceTransfer, $orderTransfer);
        }

        return $orderInvoiceCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceCollectionTransfer $orderInvoiceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceCollectionTransfer
     */
    protected function executeOrderInvoicesExpanderPlugins(OrderInvoiceCollectionTransfer $orderInvoiceCollectionTransfer): OrderInvoiceCollectionTransfer
    {
        $orderInvoiceTransfers = $orderInvoiceCollectionTransfer->getOrderInvoices()->getArrayCopy();

        foreach ($this->orderInvoicesExpanderPlugins as $orderInvoicesExpanderPlugin) {
            $orderInvoiceTransfers = $orderInvoicesExpanderPlugin->expand($orderInvoiceTransfers);
        }

        $orderInvoiceCollectionTransfer->getOrderInvoices()->exchangeArray($orderInvoiceTransfers);

        return $orderInvoiceCollectionTransfer;
    }
}
