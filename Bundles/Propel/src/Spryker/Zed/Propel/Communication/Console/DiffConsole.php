<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DiffConsole extends Console
{
    public const COMMAND_NAME = 'propel:diff';

    public const PROCESS_TIMEOUT = 300;

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Generate diff for Propel2');

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
        $this->runDependingCommand(SchemaCopyConsole::COMMAND_NAME);

        $this->info('Create diff');

        $config = Config::get(PropelConstants::PROPEL);
        $command = 'vendor/bin/propel diff --config-dir '
            . $config['paths']['phpConfDir']
            . ' --schema-dir ' . $config['paths']['schemaDir'];

        $process = new Process($command, APPLICATION_ROOT_DIR);
        $process->setTimeout(self::PROCESS_TIMEOUT);

        return $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
