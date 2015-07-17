<?php

namespace SprykerFeature\Zed\Queue\Communication\Console;

use SprykerEngine\Shared\Kernel\Store;
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
        $this->addArgument(self::TIMEOUT, InputArgument::OPTIONAL, '', 100);
        $this->addArgument(self::FETCH_SIZE, InputArgument::OPTIONAL, '', 10);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queueName = $this->getQueueName();
        $messenger = $this->getMessenger();
        $timeout = $this->input->getArgument(self::TIMEOUT);
        $fetchSize = $this->input->getArgument(self::FETCH_SIZE);

        $this->getFacade()->startWorker($queueName, $messenger, $timeout, $fetchSize);
    }

    /**
     * @return string
     */
    protected function getQueueName()
    {
        return sprintf(
            '%s.%s',
            $this->getStoreId(),
            $this->input->getArgument(self::QUEUE_NAME)
        );
    }

    /**
     * @return string
     */
    protected function getStoreId()
    {
        return Store::getInstance()->getCurrentCountry();
    }

}
