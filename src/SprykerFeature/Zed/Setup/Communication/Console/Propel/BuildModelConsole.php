<?php

namespace SprykerFeature\Zed\Setup\Communication\Console\Propel;

use SprykerFeature\Shared\Library\Config;
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
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Build propel models');

        $config = Config::get(SystemConfig::PROPEL);
        $command = 'vendor/bin/propel model:build --config-dir '
            . $config['paths']['phpConfDir']
            . ' --schema-dir ' . $config['paths']['schemaDir'] . ' --disable-namespace-auto-package'
//            . ' --enable-identifier-quoting'
        ;

        $process = new Process($command, APPLICATION_ROOT_DIR);

        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

}
