<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Communication\Console;

use Spryker\Shared\Queue\QueueConstants;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Spryker\Zed\Queue\Communication\QueueCommunicationFactory;
use Spryker\Zed\Queue\Business\QueueFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @method QueueFacade getFacade()
 * @method QueueCommunicationFactory getFactory()
 */
class QueueWorkerConsole extends Console
{

    const COMMAND_NAME = 'queue:worker:start';
    const DESCRIPTION = 'Start queue receiver workers';

    const QUEUE_RECEIVER_COMMAND = './vendor/bin/console queue:receiver:start';


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
        $startTime = time();
        $passedSeconds = 0;
        $interval = $this->getConfig(QueueConstants::QUEUE_WORKER_INTERVAL_SECONDS);
        $threshold = $this->getConfig(QueueConstants::QUEUE_WORKER_MAX_THRESHOLD_SECONDS);

        while ($passedSeconds < $threshold) {
            $this->startProcesses();

            sleep($interval);
            $passedSeconds = time() - $startTime;
        }

        return static::CODE_SUCCESS;
    }

    /**
     * @return void
     */
    protected function startProcesses()
    {
        $command = $this->getQueueCommand();
        $processors = $this->getConfig(QueueConstants::QUEUE_WORKER_PROCESSOR);
        $allProcesses = [];

        for ($i = 0; $i < $processors; $i++) {
            $process = new Process($command);
            $process->start();
            $allProcesses[] = $process;
        }

        foreach ($allProcesses as $process) {
            $process->wait();
        }
    }

    /**
     * @return string
     */
    protected function getQueueCommand()
    {
        return sprintf(
            '%s >> %s',
            self::QUEUE_RECEIVER_COMMAND,
            $this->getConfig(QueueConstants::QUEUE_WORKER_OUTPUT_FILE)
        );
    }

    /**
     * @param string $name
     *
     * @return string|int
     */
    protected function getConfig($name)
    {
        return $this->getFactory()->getQueueWorkerConfigs()[$name];
    }
}
