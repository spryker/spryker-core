<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Context;

/**
 * Class ValidateOrderIsCalculated
 * @package SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task
 */
class ValidateOrderIsCalculated extends AbstractTask
{
    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(LocatorLocatorInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @param Order   $transferOrder
     * @param Context $context
     * @param array   $logContext
     */
    public function __invoke(OrderTransfer $transferOrder, Context $context, array $logContext)
    {
        $grandTotalBefore = $transferOrder->getTotals()->getGrandTotalWithDiscounts();
        $this->locator->calculation()->facade()->recalculate($transferOrder);
        if ((int)$grandTotalBefore !== (int)$transferOrder->getTotals()->getGrandTotalWithDiscounts()) {
            $this->addError(\SprykerFeature_Shared_Checkout_Code_Messages::ERROR_PRICE_CHANGED);
        }
    }
}
