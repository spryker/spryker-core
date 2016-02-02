<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Shared\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DiffConsole extends Console
{

    const COMMAND_NAME = 'propel:diff';

    const PROCESS_TIMEOUT = 300;

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
     * @return int|null|void
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
