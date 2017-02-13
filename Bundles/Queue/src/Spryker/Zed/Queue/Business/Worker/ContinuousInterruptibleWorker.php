<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Worker;

use Spryker\Zed\Queue\Business\Worker\Plugin\InterruptPluginInterface;
use Spryker\Zed\Queue\Dependency\Consumer\ConsumerInterface;
use Spryker\Zed\Queue\Dependency\Task\TaskInterface;

class ContinuousInterruptibleWorker extends Worker
{

    const DEFAULT_PROCESS_DELAY_MILLISECONDS = 300;

    /**
     * @var InterruptPluginInterface[]
     */
    protected $interruptPlugins = [];

    /**
     * @param ConsumerInterface $consumer
     * @param TaskInterface $task
     * @param array $interruptPlugins
     */
    public function __construct(ConsumerInterface $consumer, TaskInterface $task, array $interruptPlugins)
    {
        parent::__construct($consumer, $task);

        $this->interruptPlugins = $interruptPlugins;
    }

    /**
     * @return int
     */
    public function run()
    {
        while ($this->process()) {
            if ($this->isInterrupted()) {
                break;
            }
        }

        return static::STATUS_SUCCESS;
    }

    /**
     * @return bool
     */
    protected function process()
    {
        $this->runInterruptTick();

        parent::process();

        usleep(static::DEFAULT_PROCESS_DELAY_MILLISECONDS);

        return true;
    }

    /**
     * @return void
     */
    protected function runInterruptTick()
    {
        foreach ($this->interruptPlugins as $interruptPlugin) {
            $interruptPlugin->tick();
        }
    }

    /**
     * @return bool
     */
    protected function isInterrupted()
    {
        foreach ($this->interruptPlugins as $interruptPlugin) {
            if ($interruptPlugin->isInterrupted()) {
                echo sprintf("Shutting down with interrupt from %s\n", get_class($interruptPlugin));

                return true;
            }
        }
    }
}
