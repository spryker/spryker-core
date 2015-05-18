<?php

namespace SprykerFeature\Zed\Sales\Business;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\ZedRequest\Client\RequestInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class SalesFacade extends AbstractFacade
{

    /**
     * Returns a unique identifier that can be used to group items
     * example: group items in cart by sku + priceToPay, maybe useful for vouchers
     *
     * @param SprykerFeature\Shared\Sales\Transfer\OrderItem|\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item
     * @return string
     */
    public function generateUniqueIdentifierForItem($item)
    {
        return $this->factory->createModelIdentifierGenerator($this->factory)->generateUniqueIdentifierForItem($item);
    }

    /**
     * @param \SprykerFeature\Shared\Sales\Transfer\OrderItem $item
     */
    public function addUniqueIdentifierForItem(\SprykerFeature\Shared\Sales\Transfer\OrderItem $item)
    {
        $this->factory->createModelIdentifierGenerator($this->factory)->addUniqueIdentifierForItem($item);
    }

    /**
     * @param \SprykerFeature\Shared\Sales\Transfer\OrderItemCollection $itemCollection
     */
    public function addUniqueIdentifierForItemCollection(OrderItemCollection $itemCollection)
    {
        $this->factory->createModelIdentifierGenerator($this->factory)->addUniqueIdentifierForItemCollection($itemCollection);
    }

    /**
     * @param $addressId
     * @return mixed|\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress|\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress[]
     */
    public function getAddressById($addressId)
    {
        return $this->factory->createModelAddress()->getAddressById($addressId);
    }

    /**
     * @param $addressId
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress $addressEntity
     * @param $isBilling
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddressHistory
     */
    public function addAddressToHistory($addressId, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress $addressEntity, $isBilling)
    {
        return $this->factory->createModelAddressHistory()->addAddressToHistory($addressId, $addressEntity, $isBilling);
    }

    /**
     * @param int $idSalesOrderAddress
     * @return PropelCollection
     */
    public function getHistoryBillingAddresses($idSalesOrderAddress)
    {
        return $this->factory->createModelAddressHistory()->getBillingAddresses($idSalesOrderAddress);
    }

    /**
     * @param int $idSalesOrderAddress
     * @return PropelCollection
     */
    public function getHistoryShippingAddresses($idSalesOrderAddress)
    {
        return $this->factory->createModelAddressHistory()->getShippingAddresses($idSalesOrderAddress);
    }

    /**
     * @return \SprykerFeature\Zed\Sales\Business\Facade\StateMachine
     */
    public function createFacadeStateMachine()
    {
        return $this->factory->createFacadeStateMachine();
    }

    /**
     * @param int $orderId
     * @return array|\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderComment
     */
    public function getOrderCommentsByOrderId($orderId)
    {
        return $this->factory->createModelComment()->getCommentsByOrderId($orderId);
    }

    /**
     * @param Order $transferOrder
     * @param RequestInterface $request
     * @return ModelResult
     */
    public function saveOrder(Order $transferOrder, RequestInterface $request)
    {
        return $this->factory
            ->createModelOrderManager(Locator::getInstance(), $this->factory)
            ->saveOrder($transferOrder, $request);
    }

    /**
     * @param string $sku
     * @return \Iterator
     */
    public function getReservedOrderItemsForSku($sku)
    {
        return $this->factory->createModelOrderprocessFinder()->getReservedOrderItemsForSku($sku);
    }

    /**
     * Retrieve order items defined by an array of given ids
     * @param array $itemIds
     * @return \Propel\Runtime\Collection\Collection
     */

    public function getOrderItemsByIds(array $itemIds)
    {
        return $this->factory->createModelItem()->getOrderItemsByIds($itemIds);
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return array|mixed|\PropelObjectCollection
     */
    public function getActivityLogForOrder(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        return $this->factory->createModelOrderprocessStateMachineActivityLog()->getLogForOrder($order);
    }

    /**
     * @return array|mixed|\PropelObjectCollection
     */
    public function getAllProcesses()
    {
        return \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcessQuery::create()->find();
    }

    /**
     * @param string $processId
     * @return array
     */
    public function getSimpleItemStatusOverviewIncludingIds($processId = null)
    {
        return $this->factory->createModelItem()->getSimpleItemStatusOverviewIncludingIds($processId);
    }

    /**
     * @param array $processesInMatrix
     * @return string
     */
    public function renderStatusItemOverview(array $processesInMatrix)
    {
        return $this->factory->createGuiHtmlStatusItemOverview()->init($processesInMatrix)->run()->getHtml();
    }

    /**
     * Add a note to the order notes visible in order detail page
     * @param string $message
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity
     * @param bool $isSuccess
     * @param string $commandClassName
     */
    public function addNote($message, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity, $isSuccess, $commandClassName)
    {
        $this->factory->createModelOrderNote()->addNote($message, $orderEntity, $isSuccess, $commandClassName);
    }

    /**
     * Add a note to the order notes visible in order detail page and SAVE it
     * @param string $message
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity
     * @param bool $isSuccess
     * @param string $commandClassName
     */
    public function saveNote($message, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity, $isSuccess, $commandClassName)
    {
        $this->factory->createModelOrderNote()->saveNote($message, $orderEntity, $isSuccess, $commandClassName);
    }

    /**
     *
     */
    public function saveAllNotes()
    {
        $this->factory->createModelOrderNote()->saveAllNotes();
    }

    /**
     * @deprecated Should be moved to salesrule facade when RULE Rule_IsCodeRefundable will be moved there
     *
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity
     * @return bool
     */
    public function canRefundAtLeastOneCouponCode(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity)
    {
        return $this->factory
            ->createModelOrderManager(Locator::getInstance(), $this->factory)
            ->canRefundAtLeastOneCouponCode($orderEntity);
    }

    /**
     * @param CommentCollection $collection
     * @return mixed
     */
    public function saveComment(CommentCollection $collection)
    {
        return $this->factory->createModelComment()->save($collection);
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     */
    public function markOrderAsTestOrder(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        $this->factory->createModelOrderManager(Locator::getInstance(), $this->factory)->markOrderAsTestOrder($order);
    }

    /**
     * @param $idSalesOrder
     * @return null|\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder
     */
    public function getOrderById($idSalesOrder)
    {
        return $this->factory
            ->createModelOrderManager(Locator::getInstance(), $this->factory)
            ->getOrderById($idSalesOrder);
    }

    /**
     * @return PurgeCodeUsage
     */
    public function getCommandPurgeCodeUsage()
    {
        return $this->factory->createModelOrderprocessCommandPurgeCodeUsage();
    }

    /**
     * @return Model\Orderprocess\Command\CreateInvoice
     */
    public function getCommandCreateInvoice()
    {
        return $this->factory->createModelOrderprocessCommandCreateInvoice();
    }

    /**
     * @return Model\Orderprocess\Command\CreateReverseInvoice
     */
    public function getCommandCreateReverseInvoice()
    {
        return $this->factory->createModelOrderprocessCommandCreateReverseInvoice();
    }

    /**
     * @return Model\Orderprocess\Command\CreateDeliverySlip
     */
    public function getCommandCreateDeliverySlip()
    {
        return $this->factory->createModelOrderprocessCommandCreateDeliverySlip();
    }

    /**
     * @return Model\Orderprocess\Command\Mail\OrderConfirmationMail
     */
    public function getCommandOrderConfirmationMail()
    {
        return $this->factory->createModelOrderprocessCommandMailOrderConfirmationMail();
    }

    /**
     * @return Model\Orderprocess\Command\Mail\PaymentReceivedMail
     */
    public function getCommandPaymentReceivedMail()
    {
        return $this->factory->createModelOrderprocessCommandMailPaymentReceivedMail();
    }

    /**
     * @return Model\Orderprocess\Command\Mail\PaymentReminderMail
     */
    public function getCommandPaymentReminderMail()
    {
        return $this->factory->createModelOrderprocessCommandMailPaymentReminderMail();
    }

    /**
     * @return Model\Orderprocess\Command\Mail\ShippingConfirmationMail
     */
    public function getCommandShippingConfirmationMail()
    {
        return $this->factory->createModelOrderprocessCommandMailShippingConfirmationMail();
    }

    /**
     * @return \Bos\Zed\Sales\Component\Model\Orderprocess\Command\Mail\UnderpaidReminderMail
     */
    public function getCommandUnderpaidReminderMail()
    {
        return $this->factory->createModelOrderprocessCommandMailUnderpaidReminderMail();
    }

    public function install()
    {
        return $this->getDependencyContainer()->getInstaller()->install();
    }

}
