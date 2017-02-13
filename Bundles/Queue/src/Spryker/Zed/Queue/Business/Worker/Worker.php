<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Worker;

use Spryker\Zed\Queue\Dependency\Consumer\ConsumerInterface;
use Spryker\Zed\Queue\Dependency\Task\TaskInterface;

class Worker implements WorkerInterface
{

    const STATUS_SUCCESS = 0;
    const STATUS_FAILURE = 1;

    /**
     * @var ConsumerInterface
     */
    protected $consumer;

    /**
     * @var TaskInterface
     */
    protected $task;

    /**
     * @param ConsumerInterface $consumer
     * @param TaskInterface $task
     */
    public function __construct(ConsumerInterface $consumer, TaskInterface $task)
    {
        $this->consumer = $consumer;
        $this->task = $task;
    }

    /**
     * @return int
     */
    public function run()
    {
        if ($this->process()) {
            return static::STATUS_SUCCESS;
        }

        return static::STATUS_FAILURE;
    }

    /**
     * @return bool
     */
    protected function process()
    {
        $queueMessageTransfer = $this->consumer->consume();

        if ($queueMessageTransfer === null) {
            return true;
        }

        $this->task->processMessage($queueMessageTransfer);
        $this->consumer->confirm($queueMessageTransfer);

        return true;
    }
}
