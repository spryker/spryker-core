<?php
namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow;

use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task\AbstractTask;
use SprykerFeature\Zed\Library\Workflow\ContextInterface;
use SprykerFeature\Zed\Library\Workflow\TaskInterface;
use SprykerFeature\Zed\Library\Workflow\TaskInvokerInterface;

/**
 * Class TaskInvoker
 * @package SprykerFeature\Zed\Checkout\Business\Model\Workflow
 */
class TaskInvoker implements TaskInvokerInterface
{
    /**
     * @param TaskInterface    $task
     * @param ContextInterface $context
     * @param array            $logContext
     */
    public function invokeTask(TaskInterface $task, ContextInterface $context, array $logContext)
    {
        /** @var AbstractTask $task */
        /** @var Context $context */
        assert($task instanceof AbstractTask);
        assert($context instanceof Context);

        $task($context->getTransferOrder(), $context, $logContext);
    }
}
