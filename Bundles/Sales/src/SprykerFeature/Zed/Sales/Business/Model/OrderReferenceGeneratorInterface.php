<?php

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderReferenceGeneratorInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function generateOrderReference(OrderTransfer $orderTransfer);

}
