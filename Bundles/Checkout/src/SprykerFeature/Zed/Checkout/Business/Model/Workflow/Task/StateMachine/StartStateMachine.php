<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task\StateMachine;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Context;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task\AbstractTask;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Payment\Business\Model\PaymentConstantsInterface;

/**
 * Class StartStateMachine
 * @package SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task\StateMachine
 */
class StartStateMachine extends AbstractTask
{
    /**
     * @param Order   $transferOrder
     * @param Context $context
     * @param array   $logContext
     */
    public function __invoke(OrderTransfer $transferOrder, Context $context, array $logContext)
    {
        $data = array();
        Locator::getInstance()->oms()->facade()
            ->triggerEventForNewItem($context->getOrderEntity()->getItems(), $logContext, $data);
    }
}
