<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Workflow;

/**
 * Interface TaskInvokerInterface
 */
interface TaskInvokerInterface
{

    /**
     * @param TaskInterface $task
     * @param ContextInterface $context
     * @param array $logContext
     */
    public function invokeTask(TaskInterface $task, ContextInterface $context, array $logContext);

}
