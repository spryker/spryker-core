<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Communication\Console;

use SprykerEngine\Shared\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DiffConsole extends Console
{

    const COMMAND_NAME = 'propel:diff';

    const PROCESS_TIMEOUT = 300;

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Generate diff for Propel2');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Create diff');

        $config = Config::get(SystemConfig::PROPEL);
        $command = 'vendor/bin/propel diff --config-dir '
            . $config['paths']['phpConfDir']
            . ' --schema-dir ' . $config['paths']['schemaDir']
        ;

        $process = new Process($command, APPLICATION_ROOT_DIR);
        $process->setTimeout(self::PROCESS_TIMEOUT);

        return $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

}
