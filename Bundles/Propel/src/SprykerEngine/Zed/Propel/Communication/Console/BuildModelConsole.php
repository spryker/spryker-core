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

class BuildModelConsole extends Console
{

    const COMMAND_NAME = 'setup:propel:model:build';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Build Propel2 classes');

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
        $this->info('Build propel models');

        $config = Config::get(SystemConfig::PROPEL);
        $command = 'vendor/bin/propel model:build --config-dir '
            . $config['paths']['phpConfDir']
            . ' --schema-dir ' . $config['paths']['schemaDir'] . ' --disable-namespace-auto-package'
        ;

        $process = new Process($command, APPLICATION_ROOT_DIR);

        return $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

}
