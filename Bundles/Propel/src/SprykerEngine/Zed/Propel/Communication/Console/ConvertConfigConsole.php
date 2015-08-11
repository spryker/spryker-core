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

class ConvertConfigConsole extends Console
{

    const COMMAND_NAME = 'setup:propel:config:convert';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Write Propel2 configuration');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Write propel config');

        $config = [
            'propel' => Config::get(SystemConfig::PROPEL)
        ];

        $dsn = 'pgsql:host=' . Config::get(SystemConfig::ZED_PGSQL_HOST)
            . ';dbname=' . Config::get(SystemConfig::ZED_PGSQL_DATABASE)
        ;

        $config['propel']['database']['connections']['default']['dsn'] = $dsn;
        $config['propel']['database']['connections']['default']['user'] = Config::get(SystemConfig::ZED_PGSQL_USERNAME);
        $config['propel']['database']['connections']['default']['password'] = Config::get(SystemConfig::ZED_PGSQL_PASSWORD);

        $config['propel']['database']['connections']['zed'] = $config['propel']['database']['connections']['default'];

        $json = json_encode($config, JSON_PRETTY_PRINT);

        $fileName = $config['propel']['paths']['phpConfDir']
            . DIRECTORY_SEPARATOR
            . 'propel.json'
        ;

        if (!is_dir(dirname($fileName))) {
            mkdir(dirname($fileName), 0777, true);
        }

        file_put_contents($fileName, $json);
    }

}
