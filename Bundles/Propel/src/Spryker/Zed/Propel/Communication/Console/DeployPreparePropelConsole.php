<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 */
class DeployPreparePropelConsole extends Console
{
    public const COMMAND_NAME = 'propel:deploy:prepare';
    public const DESCRIPTION = 'Prepares Propel configuration on appserver';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dependingCommands = [
            SchemaCopyConsole::COMMAND_NAME,
            BuildModelConsole::COMMAND_NAME,
        ];

        foreach ($dependingCommands as $commandName) {
            $exitCode = $this->runDependingCommand($commandName);

            if ($this->hasError()) {
                return $exitCode;
            }
        }

        return static::CODE_SUCCESS;
    }
}
