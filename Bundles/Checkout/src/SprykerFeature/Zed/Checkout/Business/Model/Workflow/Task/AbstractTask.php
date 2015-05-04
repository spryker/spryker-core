<?php

namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task;

use Generated\Shared\Transfer\SalesOrderTransfer;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Context;
use SprykerFeature\Zed\Library\Workflow\Task;

abstract class AbstractTask extends Task
{
    /**
     * @param SalesOrderTransfer $transferOrder
     * @param Context $context
     * @param array $logContext
     * @return mixed
     */
    abstract public function __invoke(SalesOrderTransfer $transferOrder, Context $context, array $logContext);
}
