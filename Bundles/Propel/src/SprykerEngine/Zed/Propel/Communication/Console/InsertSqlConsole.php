<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class InsertSqlConsole extends Console
{

    const COMMAND_NAME = 'setup:propel:sql:insert';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Insert generated SQL into database');

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
        $this->info('Insert SQL');

        $command = 'vendor/bin/propel sql:insert --config-dir config/Zed';

        $process = new Process($command, APPLICATION_ROOT_DIR);

        return $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

}
