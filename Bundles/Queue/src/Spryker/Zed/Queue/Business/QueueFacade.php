<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Queue\Business\QueueBusinessFactory getFactory()
 */
class QueueFacade extends AbstractFacade implements QueueFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $queueName
     * @param array $options
     *
     * @return void
     */
    public function startTask($queueName, array $options = [])
    {
        $this->getFactory()
            ->createTask()
            ->run($queueName, $options);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $command
     * @param \Symfony\Component\Console\Output\OutputInterface $output
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
