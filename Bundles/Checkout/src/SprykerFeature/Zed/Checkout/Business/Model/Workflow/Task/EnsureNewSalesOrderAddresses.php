<?php
namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task;

use Generated\Shared\Transfer\SalesOrderTransfer;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Context;

/**
 * Class EnsureNewSalesOrderAddresses
 * @package SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task
 */
class EnsureNewSalesOrderAddresses extends AbstractTask
{

    /**
     * @param Order   $transferOrder
     * @param Context $context
     * @param array   $logContext
     */
    public function __invoke(SalesOrderTransfer $transferOrder, Context $context, array $logContext)
    {
        // unset id, because we always want to store a new address for each order
        $transferOrder->getShippingAddress()->setIdSalesOrderAddress(null);
        $transferOrder->getBillingAddress()->setIdSalesOrderAddress(null);
    }
}
