<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Communication\Console;

use Generated\Shared\Transfer\QueueDumpRequestTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Queue\Business\QueueFacadeInterface getFacade()
 * @method \Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Queue\Communication\QueueCommunicationFactory getFactory()
 */
class QueueDumpConsole extends Console
{
    public const COMMAND_NAME = 'queue:dump';
    protected const DESCRIPTION = 'Dump queue content';

    protected const OPTION_LIMIT = 'limit';
    protected const OPTION_LIMIT_SHORT = 'l';
    protected const OPTION_LIMIT_DEFAULT = 10;
    protected const OPTION_LIMIT_DESCRIPTION = 'Defines what amount of events must be read from the queue. If limit is not defined the whole queue will be dumped';

    protected const OPTION_FORMAT = 'format';
    protected const OPTION_FORMAT_SHORT = 'f';
    protected const OPTION_FORMAT_DEFAULT = 'json';
    protected const OPTION_FORMAT_DESCRIPTION = 'Defines dump queue message export format (e.g json, csv)';

    protected const OPTION_ACK = 'ack';
    protected const OPTION_ACK_SHORT = 'k';
    protected const OPTION_ACK_DEFAULT = 0;
    protected const OPTION_ACK_DESCRIPTION = 'Defines if queue messages must be acknowledged';

    public const ARGUMENT_QUEUE = 'queue';
    public const ARGUMENT_QUEUE_DESCRIPTION = 'Name of the queue for receiving the messages';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);
        $this->addArgument(static::ARGUMENT_QUEUE, InputArgument::REQUIRED, static::DESCRIPTION);
        $this->addOption(static::OPTION_ACK, static::OPTION_ACK_SHORT, InputOption::VALUE_OPTIONAL, static::OPTION_ACK_DESCRIPTION, static::OPTION_ACK_DEFAULT);
        $this->addOption(static::OPTION_LIMIT, static::OPTION_LIMIT_SHORT, InputOption::VALUE_OPTIONAL, static::OPTION_LIMIT_DESCRIPTION, static::OPTION_LIMIT_DEFAULT);
        $this->addOption(static::OPTION_FORMAT, static::OPTION_FORMAT_SHORT, InputOption::VALUE_OPTIONAL, static::OPTION_FORMAT_DESCRIPTION, static::OPTION_FORMAT_DEFAULT);

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
        $queueNameRequestTransfer = $this->createQueueDumpRequestTransfer($input);

        $queueDumpResponseTransfer = $this->getFacade()->queueDump($queueNameRequestTransfer);

        $output->write($queueDumpResponseTransfer->getMessage());

        return static::CODE_SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return \Generated\Shared\Transfer\QueueDumpRequestTransfer
     */
    protected function createQueueDumpRequestTransfer(InputInterface $input): QueueDumpRequestTransfer
    {
        $queueName = $input->getArgument(static::ARGUMENT_QUEUE);
        $limit = (int)$input->getOption(static::OPTION_LIMIT);
        $format = $input->getOption(static::OPTION_FORMAT);
        $acknowledge = $input->getOption(static::OPTION_ACK);

        return (new QueueDumpRequestTransfer())
            ->setQueueName($queueName)
            ->setLimit($limit)
            ->setFormat($format)
            ->setAcknowledge($acknowledge);
    }
}
