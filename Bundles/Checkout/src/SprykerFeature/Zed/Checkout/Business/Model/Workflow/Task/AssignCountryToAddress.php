<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Context;

class AssignCountryToAddress extends AbstractPrepareAddress
{
    /**
     * @param OrderTransfer $transferOrder
     * @param Context $context
     * @param array $logContext
     */
    public function __invoke(OrderTransfer $transferOrder, Context $context, array $logContext)
    {
        $transferOrder->getBillingAddress()->setFkMiscCountry($this->getFkCountry($transferOrder->getBillingAddress()));
        $transferOrder->getShippingAddress()->setFkMiscCountry($this->getFkCountry($transferOrder->getShippingAddress()));
    }
}
