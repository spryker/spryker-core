<?php
namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task\Propel;

use Generated\Shared\Transfer\SalesOrderTransfer;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Context;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task\AbstractTask;

class BeginTransaction extends AbstractTask
{
    /**
     * @param SalesOrderTransfer $transferOrder
     * @param Context $context
     * @param array $logContext
     */
    public function __invoke(SalesOrderTransfer $transferOrder, Context $context, array $logContext)
    {
        $connection = \Propel\Runtime\Propel::getConnection();
        $connection->beginTransaction();
    }
}
