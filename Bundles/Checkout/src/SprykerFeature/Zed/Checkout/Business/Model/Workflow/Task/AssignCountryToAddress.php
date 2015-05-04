<?php
namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task;

use Generated\Shared\Transfer\SalesOrderTransfer;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Context;

class AssignCountryToAddress extends AbstractPrepareAddress
{
    /**
     * @param SalesOrderTransfer $transferOrder
     * @param Context $context
     * @param array $logContext
     */
    public function __invoke(SalesOrderTransfer $transferOrder, Context $context, array $logContext)
    {
        $transferOrder->getBillingAddress()->setFkMiscCountry($this->getFkCountry($transferOrder->getBillingAddress()));
        $transferOrder->getShippingAddress()->setFkMiscCountry($this->getFkCountry($transferOrder->getShippingAddress()));
    }
}
