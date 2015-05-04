<?php
namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow;

use ArrayObject;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use Generated\Shared\Transfer\SalesOrderTransfer;
use SprykerFeature\Zed\Library\Workflow\ContextInterface;
use SprykerFeature\Shared\ZedRequest\Client\RequestInterface;

class Context extends ArrayObject implements
    ContextInterface
{
    /**
     * @var Order
     */
    protected $transferOrder;

    /**
     * @var RequestInterface
     */
    protected $transferRequest;

    /**
     * @var SpySalesOrder
     */
    protected $orderEntity;

    /**
     * @param SpySalesOrder $orderEntity
     */
    public function setOrderEntity(SpySalesOrder $orderEntity)
    {
        $this->orderEntity = $orderEntity;
    }

    /**
     * @return SpySalesOrder
     */
    public function getOrderEntity()
    {
        return $this->orderEntity;
    }

    /**
     * @param Order $transferOrder
     */
    public function setTransferOrder(Order $transferOrder)
    {
        $this->transferOrder = $transferOrder;
    }

    /**
     * @return Order
     */
    public function getTransferOrder()
    {
        return $this->transferOrder;
    }

    /**
     * @param RequestInterface $transferRequest
     * @return $this
     */
    public function setTransferRequest(RequestInterface $transferRequest)
    {
        $this->transferRequest = $transferRequest;
        return $this;
    }

    /**
     * @return RequestInterface
     */
    public function getTransferRequest()
    {
        return $this->transferRequest;
    }
}
