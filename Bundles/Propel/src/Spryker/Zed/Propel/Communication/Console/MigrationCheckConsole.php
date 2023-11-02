<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 */
class MigrationCheckConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'propel:migration:check';

    /**
     * @var string
     */
    public const COMMAND_DESCRIPTION = 'propel:migration:check';

    /**
     * @var int
     */
    public const CODE_CHANGES = 3;

    /**
     * @var string
     */
    protected const COMMAND_OPTION_LAST_VERSION = 'last-version';

    /**
     * @var string
     */
    protected const COMMAND_OPTION_LAST_VERSION_FULL = '--last-version';

    /**
     * @var string
     */
    protected const COMMAND_OPTION_LAST_VERSION_DESCRIPTION = 'Use this option to receive the version of the last executed migration.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::COMMAND_DESCRIPTION);
        $this->addOption(static::COMMAND_OPTION_LAST_VERSION, null, InputOption::VALUE_NONE, static::COMMAND_OPTION_LAST_VERSION_DESCRIPTION);

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
        $this->info($this->getDescription());

        $command = $this->getFactory()->createMigrationStatusCommand();

        $commandLineArguments = [];
        if ($input->getOption(static::COMMAND_OPTION_LAST_VERSION)) {
            $commandLineArguments[static::COMMAND_OPTION_LAST_VERSION_FULL] = $input->getOption(static::COMMAND_OPTION_LAST_VERSION);
        }

        return $this->getFactory()->createPropelCommandRunner()->runCommand(
            $command,
            $this->getDefinition(),
            $output,
            $commandLineArguments,
        );
    }
}
