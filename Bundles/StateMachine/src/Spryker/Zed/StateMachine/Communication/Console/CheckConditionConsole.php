<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface getFacade()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\StateMachine\Communication\StateMachineCommunicationFactory getFactory()
 */
class CheckConditionConsole extends Console
{
    public const COMMAND_NAME = 'state-machine:check-condition';
    public const COMMAND_DESCRIPTION = 'Check conditions';
    public const ARGUMENT_STATE_MACHINE_NAME = 'state machine name';
    public const OPTION_STATE_MACHINE_NAME = 'state-machine-name';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::COMMAND_DESCRIPTION);

        $this->addArgument(
            static::ARGUMENT_STATE_MACHINE_NAME,
            InputArgument::OPTIONAL,
            'Name of state machine to execute condition check'
        );

        $this->addOption(
            static::OPTION_STATE_MACHINE_NAME,
            's',
            InputOption::VALUE_REQUIRED,
            '(deprecated) Name of state machine to execute condition check'
        );

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $optionStateMachineName = $this->input->getOption(static::OPTION_STATE_MACHINE_NAME);
        $argumentStateMachineName = $this->input->getArgument(static::ARGUMENT_STATE_MACHINE_NAME);

        $isValidArgument = $this->validateStateMachineNameArgument($argumentStateMachineName);
        if ($isValidArgument === null) {
            $this->validateStateMachineNameOption($optionStateMachineName);
        }
        if ($isValidArgument === false) {
            return null;
        }

        $affected = $this->getFacade()->checkConditions($isValidArgument === null ? $optionStateMachineName : $argumentStateMachineName);

        if ($output->isVerbose()) {
            $output->writeln('Affected: ' . $affected);
        }
    }

    /**
     * @param string|null $stateMachineName
     *
     * @return void
     */
    protected function validateStateMachineNameOption($stateMachineName)
    {
        if ($stateMachineName === null) {
            $this->info('No state machine name was provided.');

            return;
        }

        if (!$this->getFacade()->stateMachineExists($stateMachineName)) {
            $this->info(sprintf('State machine "%s" was not found. ', $stateMachineName));
        }
    }

    /**
     * @param string|null $stateMachineName
     *
     * @return bool|null
     */
    protected function validateStateMachineNameArgument($stateMachineName)
    {
        if ($stateMachineName === null) {
            return null;
        }

        if ($this->getFacade()->stateMachineExists($stateMachineName)) {
            return true;
        }

        $this->error(sprintf('State machine "%s" was not found.', $stateMachineName));

        return false;
    }
}
