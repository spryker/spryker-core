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

class BuildSqlConsole extends Console
{

    const COMMAND_NAME = 'propel:sql:build';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Build SQL with Propel2');

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
        $this->info('Build sql');

        $config = Config::get(SystemConfig::PROPEL);
        $command = 'vendor/bin/propel sql:build --config-dir '
            . $config['paths']['phpConfDir']
            . ' --schema-dir ' . $config['paths']['schemaDir']
        ;

        $process = new Process($command, APPLICATION_ROOT_DIR);

        return $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

}
