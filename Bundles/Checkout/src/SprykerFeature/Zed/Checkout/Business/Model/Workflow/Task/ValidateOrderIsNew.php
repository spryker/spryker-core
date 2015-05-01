<?php

namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task;

use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Context;

class ValidateOrderIsNew extends AbstractTask
{

    /**
     * @param Order   $transferOrder
     * @param Context $context
     * @param array   $logContext
     */
    public function __invoke(Order $transferOrder, Context $context, array $logContext)
    {
        $id = $transferOrder->getIdSalesOrder();
        if (!empty($id)) {
            $this->addError(\SprykerFeature_Shared_Checkout_Code_Messages::ERROR_ORDER_IS_ALREADY_SAVED);
        }
    }
}
