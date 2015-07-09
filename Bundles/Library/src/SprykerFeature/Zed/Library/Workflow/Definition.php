<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Workflow;

/**
 * Abstract implementation of the definition interface
 */
abstract class Definition implements DefinitionInterface
{

    /**
     * @return TaskInterface[]
     */
    abstract protected function getTasks();

    /**
     * @return ContextInterface
     */
    abstract protected function buildContext();

    /**
     * @return TaskInvokerInterface
     */
    abstract protected function getTaskInvoker();

    /**
     * @param ContextInterface $context
     *
     * @return mixed
     */
    abstract protected function getSuccessResultFromContext(ContextInterface $context);

    /**
     * @param array $logContext
     *
     * @return mixed
     */
    public function run(array $logContext)
    {
        $context = $this->buildContext();
        $invoker = $this->getTaskInvoker();
        /** @var TaskInterface $task */
        foreach ($this->getTasks() as $task) {
            $invoker->invokeTask($task, $context, $logContext);
            if (!$task->isSuccess()) {
                // If something failed, exit immediately
                $result = new \SprykerEngine\Zed\Kernel\Business\ModelResult();
                $result->addErrors($task->getErrors());

                return $result;
            }
        }

        return $this->getSuccessResultFromContext($context);
    }

}
