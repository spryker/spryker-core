<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Queue\Dependency\Consumer\ConsumerInterface;
use Spryker\Zed\Queue\Dependency\Task\TaskInterface;

/**
 * @method QueueBusinessFactory getFactory()
 */
class QueueFacade extends AbstractFacade implements QueueFacadeInterface
{

    /**
     * @param ConsumerInterface $consumer
     * @param TaskInterface $task
     *
     * @return void
     */
    public function runContinuousInterruptibleWorker(ConsumerInterface $consumer, TaskInterface $task)
    {
        $this
            ->getFactory()
            ->createContinuousInterruptibleWorker($consumer, $task)
            ->run();
    }
}
