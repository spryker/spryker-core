<?php

namespace SprykerFeature\Zed\Sales\Business;

use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\ZedRequest\Client\RequestInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Sales\SalesDependencyProvider;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class SalesFacade extends AbstractFacade
{

    /**
     * @param CommentTransfer $commentTransfer
     *
     * @return CommentTransfer
     */
    public function saveComment(CommentTransfer $commentTransfer)
    {
        $commentsManager = $this->getDependencyContainer()->createCommentsManager();
        $commentsManager->saveComment($commentTransfer);

        return $commentsManager->convertToTransfer();
    }

    /**
     * @param int $orderId
     *
     * @return array
     */
    public function getOrderItemsArrayByOrderId($orderId)
    {
        $orderManager = $this->getDependencyContainer()->createOrderDetailsManager();

        return $orderManager->getOrderItemsArrayByOrderId($orderId);
    }

    /**
     * @param int $orderItemId
     *
     * @return array
     */
    public function getOrderItemManualEvents($orderItemId)
    {
        return $this->getDependencyContainer()
            ->getProvidedDependency(SalesDependencyProvider::FACADE_OMS)
            ->getManualEvents($orderItemId)
        ;
    }

    /**
     * @param int $orderItemId
     *
     * @return OrderItemsTransfer
     */
    public function getOrderItemById($orderItemId)
    {
        return $this->getDependencyContainer()
            ->getProvidedDependency(SalesDependencyProvider::FACADE_OMS)
            ->getOrderItemById($orderItemId)
        ;
    }

    /**
     * @return OrderDetailsManager
     */
    public function createOrderDetailsModel()
    {
        $model = $this->getDependencyContainer()
            ->createOrderDetailsManager()
        ;

        return $model;
    }

    /**
     * @param OrderTransfer $transferOrder
     * @param RequestInterface $request
     *
     * @return ModelResult
     */
    public function saveOrder(OrderTransfer $transferOrder, RequestInterface $request)
    {
        return $this->factory
            ->createModelOrderManager(Locator::getInstance(), $this->factory)
            ->saveOrder($transferOrder, $request);
    }
}
