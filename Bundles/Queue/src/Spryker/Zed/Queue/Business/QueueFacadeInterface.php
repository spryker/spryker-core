<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business;

use Symfony\Component\Console\Output\OutputInterface;

interface QueueFacadeInterface
{
    /**
     * Specification
     *  - Starts receiving and processing messages task for one specific queue
     *
     * @api
     *
     * @param string $queueName
     * @param array $options
     *
     * @return void
     */
    public function startTask($queueName, array $options = []);

    /**
     * Specification
     *  - Starts multiple task/processes per queue
     *
     * @api
     *
     * @param string $command
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function startWorker($command, OutputInterface $output);
}
