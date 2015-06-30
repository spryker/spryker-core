<?php

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Sales\Dependency\Plugin\OrderReferenceGeneratorInterface;

class MockOrderReferenceGenerator implements OrderReferenceGeneratorInterface
{

    //TODO replace this mock order reference generator!

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function generateOrderReference(OrderTransfer $orderTransfer)
    {
        return uniqid();
    }
}
