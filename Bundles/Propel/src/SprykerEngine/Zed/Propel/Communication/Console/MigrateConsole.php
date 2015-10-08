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

const RETURN_CODE_ERROR = 1;

class MigrateConsole extends Console
{

    const COMMAND_NAME = 'setup:propel:migrate';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Migrate database');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Run migrations');

        $config = Config::get(SystemConfig::PROPEL);
        $command = 'vendor/bin/propel migrate --config-dir '
            . $config['paths']['phpConfDir']
        ;

        $process = new Process($command, APPLICATION_ROOT_DIR);

        return $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

}
