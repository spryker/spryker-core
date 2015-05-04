<?php
namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task;

use Generated\Shared\Transfer\SalesOrderTransfer;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Context;

/**
 * Class AssignCountryToAddress
 * @package SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task
 */
class AssignCountryToAddress extends AbstractPrepareAddress
{
    /**
     * @param Order   $transferOrder
     * @param Context $context
     * @param array   $logContext
     */
    public function __invoke(Order $transferOrder, Context $context, array $logContext)
    {
        $transferOrder->getBillingAddress()->setFkMiscCountry($this->getFkCountry($transferOrder->getBillingAddress()));
        $transferOrder->getShippingAddress()->setFkMiscCountry($this->getFkCountry($transferOrder->getShippingAddress()));
    }
}
