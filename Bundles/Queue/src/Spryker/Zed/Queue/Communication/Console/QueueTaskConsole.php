<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Communication\Console;

use Spryker\Shared\Queue\QueueConfig;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Queue\Business\QueueFacadeInterface getFacade()
 */
class QueueTaskConsole extends Console
{
    public const COMMAND_NAME = 'queue:task:start';
    public const DESCRIPTION = 'Start queue task for specific queue';

    public const OPTION_NO_ACK = 'no-ack';
    public const OPTION_NO_ACK_SHORT = 'k';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);
        $this->addOption(static::OPTION_NO_ACK, static::OPTION_NO_ACK_SHORT, InputOption::VALUE_NONE, 'Disable the acknowledgment to keep the message in queue');
        $this->addArgument('queue', InputArgument::REQUIRED, 'Name of the queue for receiving the messages');

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
        $options = [
            QueueConfig::CONFIG_QUEUE_OPTION_NO_ACK => $input->getOption(static::OPTION_NO_ACK),
        ];

        $this->getFacade()->startTask($input->getArgument('queue'), $options);

        return static::CODE_SUCCESS;
    }
}
