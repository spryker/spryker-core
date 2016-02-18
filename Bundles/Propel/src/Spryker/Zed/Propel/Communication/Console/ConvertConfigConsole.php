<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Shared\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertConfigConsole extends Console
{

    const COMMAND_NAME = 'propel:config:convert';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Write Propel2 configuration');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Write propel config');

        $config = [
            'propel' => Config::get(PropelConstants::PROPEL),
        ];

        $dsn = Config::get(PropelConstants::ZED_DB_ENGINE) . ':host=' . Config::get(PropelConstants::ZED_DB_HOST)
            . ';dbname=' . Config::get(PropelConstants::ZED_DB_DATABASE);

        $config['propel']['database']['connections']['default']['dsn'] = $dsn;
        $config['propel']['database']['connections']['default']['user'] = Config::get(PropelConstants::ZED_DB_USERNAME);
        $config['propel']['database']['connections']['default']['password'] = Config::get(PropelConstants::ZED_DB_PASSWORD);

        $config['propel']['database']['connections']['zed'] = $config['propel']['database']['connections']['default'];

        $json = json_encode($config, JSON_PRETTY_PRINT);

        $fileName = $config['propel']['paths']['phpConfDir']
            . DIRECTORY_SEPARATOR
            . 'propel.json';

        if (!is_dir(dirname($fileName))) {
            mkdir(dirname($fileName), 0777, true);
        }

        file_put_contents($fileName, $json);
    }

}
