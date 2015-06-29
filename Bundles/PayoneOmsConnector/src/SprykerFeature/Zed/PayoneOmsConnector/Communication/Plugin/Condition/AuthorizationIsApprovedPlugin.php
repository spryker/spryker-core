<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication\Plugin\Condition;


use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use Generated\Shared\Transfer\OrderTransfer;

class AuthorizationIsApprovedPlugin extends AbstractPlugin
{
    /**
     * @param SpySalesOrderItem $orderItem
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $order = $orderItem->getOrder();

        if (isset(self::$resultCache[$order->getPrimaryKey()])) {
            return self::$resultCache[$order->getPrimaryKey()];
        }

        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($order->toArray());

        $isSuccess = $this->getDependencyContainer()->createPayoneFacade()->isAuthorizationApproved($orderTransfer);
        self::$resultCache[$order->getPrimaryKey()] = $isSuccess;

        return $isSuccess;
    }

}
