<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Sales\Business\SalesBusinessFactory getFactory()
 */
class SalesFacade extends AbstractFacade implements SalesFacadeInterface
{
    /**
     * Specification:
     *  - Returns persisted order information stored into OrderTransfer
     *  - Hydrates order by calling HydrateOrderPlugin's registered in project dependency provider.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()
            ->createOrderHydrator()
            ->hydrateOrderTransferFromPersistenceByIdSalesOrder($idSalesOrder);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByIdSalesOrderItem($idSalesOrderItem)
    {
        return $this->getFactory()
            ->createOrderReader()
            ->findOrderByIdSalesOrderItem($idSalesOrderItem);
    }

    /**
     * Specification:
     *  - Returns a list of of orders for the given customer id and (optional) filters.
     *  - Aggregates order totals calls -> SalesAggregator
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getCustomerOrders(OrderListTransfer $orderListTransfer, $idCustomer)
    {
        return $this->getFactory()
            ->createCustomerOrderReader()
            ->getOrders($orderListTransfer, $idCustomer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getPaginatedCustomerOrders(OrderListTransfer $orderListTransfer, $idCustomer)
    {
        return $this->getFactory()
            ->createPaginatedCustomerOrderReader()
            ->getOrders($orderListTransfer, $idCustomer);
    }

    /**
     * Specification:
     *  - Returns the order for the given customer id and sales order id.
     *  - Aggregates order totals calls -> SalesAggregator
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getCustomerOrder(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createOrderHydrator()
            ->getCustomerOrder($orderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Use saveSalesOrder() instead
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->getFactory()
            ->createOrderSaver()
            ->saveOrder($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveSalesOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $this->getFactory()
            ->createSalesOrderSaver()
            ->saveOrderSales($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * Specification:
     * - Adds username to comment
     * - Saves comment to database
     *
     * @CR check why return wrong
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function saveComment(CommentTransfer $commentTransfer)
    {
        return $this->getFactory()
            ->createOrderCommentSaver()
            ->save($commentTransfer);
    }

    /**
     * Specification:
     * - Returns the distinct states of all order items for the given order id
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return string[]
     */
    public function getDistinctOrderStates($idSalesOrder)
    {
        return $this->getFactory()
            ->createOrderReader()
            ->getDistinctOrderStates($idSalesOrder);
    }

    /**
     * Specification:
     * - Returns all comments for the given order id
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderDetailsCommentsTransfer
     */
    public function getOrderCommentsByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()
            ->createOrderCommentReader()
            ->getCommentsByIdSalesOrder($idSalesOrder);
    }

    /**
     * Specification
     * - Updates sales order with data from order transfer
     * - Returns true if order was successfully updated
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function updateOrder(OrderTransfer $orderTransfer, $idSalesOrder)
    {
        return $this->getFactory()
            ->createOrderUpdater()
            ->update($orderTransfer, $idSalesOrder);
    }

    /**
     * Specification:
     * - Replaces all values of the order address by the values from the addresses transfer
     * - Returns true if order was successfully updated
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressesTransfer
     * @param int $idAddress
     *
     * @return boolean
     */
    public function updateOrderAddress(AddressTransfer $addressesTransfer, $idAddress)
    {
        return $this->getFactory()
            ->createOrderAddressUpdater()
            ->update($addressesTransfer, $idAddress);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer|null $checkoutResponseTransfer Deprecated: Parameter is not used
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandSalesOrder(QuoteTransfer $quoteTransfer, ?CheckoutResponseTransfer $checkoutResponseTransfer = null)
    {
        return $this->getFactory()
            ->createOrderExpander()
            ->expandSalesOrder($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function transformItem(ItemTransfer $itemTransfer): ArrayObject
    {
        return $this->getFactory()
            ->createItemTransformer()
            ->transformItem($itemTransfer);
    }
}
