<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SalesCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\SalesCheckoutConnector\Dependency\Facade\SalesCheckoutConnectorToSalesInterface;

class SalesOrderSaver implements SalesOrderSaverInterface
{

    /**
     * @var \Spryker\Zed\SalesCheckoutConnector\Dependency\Facade\SalesCheckoutConnectorToSalesInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\SalesCheckoutConnector\Dependency\Facade\SalesCheckoutConnectorToSalesInterface $salesFacade
     */
    public function __construct(SalesCheckoutConnectorToSalesInterface $salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $orderTransfer = $this->salesFacade->saveOrder($quoteTransfer);

        $saveOrderTransfer = $this->getSaveOrderTransfer($checkoutResponseTransfer);
        $this->hydrateSaveOrderTransfer($saveOrderTransfer, $orderTransfer);

        $checkoutResponseTransfer->setSaveOrder($saveOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function getSaveOrderTransfer(CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $saveOrderTransfer = $checkoutResponseTransfer->getSaveOrder();
        if ($saveOrderTransfer === null) {
            $saveOrderTransfer = $this->createSaveOrderTransfer();
        }

        return $saveOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array|int[]
     */
    protected function getSalesOrderIds(OrderTransfer $orderTransfer)
    {
        $orderItemIds = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $orderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }
        return $orderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function hydrateSaveOrderTransfer(SaveOrderTransfer $saveOrderTransfer, OrderTransfer $orderTransfer)
    {
        $saveOrderTransfer->fromArray($orderTransfer->toArray(), true);
        $saveOrderTransfer->setOrderItemIds($this->getSalesOrderIds($orderTransfer));
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function createSaveOrderTransfer()
    {
        return new SaveOrderTransfer();
    }

}
