<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Queue\Business\QueueFacade getFacade()
 */
class QueueWorkerConsole extends Console
{
    const COMMAND_NAME = 'queue:worker:start';
    const DESCRIPTION = 'Start queue workers';

    const QUEUE_RUNNER_COMMAND = APPLICATION_VENDOR_DIR . '/bin/console queue:task:start';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFacade()->startWorker(self::QUEUE_RUNNER_COMMAND, $output);

        return static::CODE_SUCCESS;
    }
}
