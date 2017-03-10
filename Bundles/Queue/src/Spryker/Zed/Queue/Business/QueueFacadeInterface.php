<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business;

use Symfony\Component\Console\Output\OutputInterface;

interface QueueFacadeInterface
{

    /**
     * @param string $queueName
     *
     * @return void
     */
    public function startTask($queueName);

    /**
     * @param string $command
     * @param OutputInterface $output
     *
     * @return void
     */
    public function startWorker($command, OutputInterface $output);
}
