<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method QueueBusinessFactory getFactory()
 */
class QueueFacade extends AbstractFacade implements QueueFacadeInterface
{

    /**
     * @param string $queueName
     *
     * @return void
     */
    public function startTask($queueName)
    {
        $this->getFactory()
            ->createTask()
            ->run($queueName);
    }

    /**
     * @param string $command
     * @param OutputInterface $output
     *
     * @return void
     */
    public function startWorker($command, OutputInterface $output)
    {
        $this->getFactory()
            ->createWorker($output)
            ->start($command);
    }
}
