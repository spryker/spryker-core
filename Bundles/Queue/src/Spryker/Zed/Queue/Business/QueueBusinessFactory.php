<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Queue\Business\Worker\ContinuousInterruptibleWorker;
use Spryker\Zed\Queue\Business\Worker\Plugin\InterruptPluginInterface;
use Spryker\Zed\Queue\Business\Worker\Plugin\IterationInterruptPlugin;
use Spryker\Zed\Queue\Business\Worker\Plugin\SignalInterruptPlugin;
use Spryker\Zed\Queue\Business\Worker\Plugin\TimeoutInterruptPlugin;
use Spryker\Zed\Queue\Business\Worker\WorkerInterface;
use Spryker\Zed\Queue\Dependency\Consumer\ConsumerInterface;
use Spryker\Zed\Queue\Dependency\Task\TaskInterface;

class QueueBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param ConsumerInterface $consumer
     * @param TaskInterface $task
     *
     * @return WorkerInterface
     */
    public function createContinuousInterruptibleWorker(ConsumerInterface $consumer, TaskInterface $task)
    {
        return new ContinuousInterruptibleWorker(
            $consumer,
            $task,
            $this->getInterruptPlugins()
        );
    }

    /**
     * @return InterruptPluginInterface[]
     */
    protected function getInterruptPlugins()
    {
        return [
            new IterationInterruptPlugin(),
            new TimeoutInterruptPlugin(),
        ];
    }
}
