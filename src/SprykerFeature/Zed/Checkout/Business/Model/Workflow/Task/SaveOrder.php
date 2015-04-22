<?php

namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Context;

/**
 * Class SaveOrder
 * @package SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task
 */
class SaveOrder extends AbstractTask
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
    public function __invoke(Order $transferOrder, Context $context, array $logContext)
    {
        $result = $this->locator->sales()->facade()->saveOrder($transferOrder, $context->getTransferRequest());
        if (!$result->isSuccess()) {
            $this->addError(\SprykerFeature_Shared_Checkout_Code_Messages::ERROR_ORDER_NOT_SAVED);
            $this->addErrors($result->getErrors());
        } else {
            /* @var \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $entity */
            $entity = $result->getEntity();
            $context->setOrderEntity($entity);
            $transferOrder->setIdSalesOrder($entity->getIdSalesOrder());
            $transferOrder->setIncrementId($entity->getIncrementId());
        }
    }
}
