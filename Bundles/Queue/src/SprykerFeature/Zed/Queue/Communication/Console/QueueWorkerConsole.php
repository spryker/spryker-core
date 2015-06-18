<?php

namespace SprykerFeature\Zed\Queue\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use SprykerFeature\Zed\Queue\Business\QueueFacade;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method QueueFacade getFacade()
 */
class QueueWorkerConsole extends Console
{
    const COMMAND_NAME = 'queue:start-worker';
    const COMMAND_DESCRIPTION = 'processes queue messages via tasks';
    const QUEUE_NAME = 'queue-name';
    const TIMEOUT = 'timeout';
    const FETCH_SIZE = 'fetch-size';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::COMMAND_DESCRIPTION);
        $this->addArgument(self::QUEUE_NAME, InputArgument::REQUIRED);
        $this->addArgument(self::TIMEOUT, InputArgument::OPTIONAL);
        $this->addArgument(self::FETCH_SIZE, InputArgument::OPTIONAL);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queueName = $this->input->getArgument(self::QUEUE_NAME);
        $timeout = $this->getTimeout($input);
        $fetchSize = $this->getFetchSize($input);

        $this->getFacade()->startWorker($queueName, $timeout, $fetchSize);
    }

    /**
     * @param InputInterface $input
     *
     * @return array|null
     */
    protected function getTimeout(InputInterface $input)
    {
        $timeout = null;
        if ($input->getArgument(self::TIMEOUT)) {
            $timeout = $input->getArgument(self::TIMEOUT);
        }

        return $timeout;
    }

    /**
     * @param InputInterface $input
     *
     * @return array|null
     */
    protected function getFetchSize(InputInterface $input)
    {
        $fetchSize = null;
        if ($input->getArgument(self::FETCH_SIZE)) {
            $fetchSize = $input->getArgument(self::FETCH_SIZE);
        }

        return $fetchSize;
    }
}
