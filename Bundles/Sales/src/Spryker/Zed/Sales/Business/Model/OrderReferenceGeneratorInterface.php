<?php

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderReferenceGeneratorInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function generateOrderReference(OrderTransfer $orderTransfer);

}
