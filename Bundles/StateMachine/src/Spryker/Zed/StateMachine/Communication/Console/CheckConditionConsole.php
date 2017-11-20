<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface getFacade()
 */
class CheckConditionConsole extends Console
{
    const COMMAND_NAME = 'state-machine:check-condition';
    const COMMAND_DESCRIPTION = 'Check conditions';
    const ARGUMENT_STATE_MACHINE_NAME = 'state machine name';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::COMMAND_DESCRIPTION);

        $this->addArgument(
            static::ARGUMENT_STATE_MACHINE_NAME,
            InputArgument::REQUIRED,
            'Name of state machine to execute condition check'
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
        $stateMachineName = $this->input->getArgument(static::ARGUMENT_STATE_MACHINE_NAME);

        $hasHandler = $this->getFacade()->hasHandler($stateMachineName);
        if (!$hasHandler) {
            $this->error(sprintf('State machine "%s" was not found.', $stateMachineName));
            return;
        }

        $this->getFacade()->checkConditions($stateMachineName);
    }
}
