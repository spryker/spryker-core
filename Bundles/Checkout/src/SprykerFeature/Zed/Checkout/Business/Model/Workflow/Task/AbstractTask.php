<?php

namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Context;
use SprykerFeature\Zed\Library\Workflow\Task;

abstract class AbstractTask extends Task
{
    /**
     * @param OrderTransfer $transferOrder
     * @param Context $context
     * @param array $logContext
     * @return mixed
     */
    abstract public function __invoke(OrderTransfer $transferOrder, Context $context, array $logContext);
}
