<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacade getFacade()
 */
class CheckTimeoutConsole extends Console
{
    const COMMAND_NAME = 'state-machine:check-timeout';
    const COMMAND_DESCRIPTION = 'Check timeouts';
    const OPTION_STATE_MACHINE_NAME = 'state-machine-name';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::COMMAND_DESCRIPTION);

        $this->addOption(
            self::OPTION_STATE_MACHINE_NAME,
            's',
            InputOption::VALUE_REQUIRED,
            'Name of state machine to trigger timeout expired items'
        );

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stateMachineName = $this->input->getOption(self::OPTION_STATE_MACHINE_NAME);

        $this->getFacade()->checkTimeouts($stateMachineName);
    }
}
