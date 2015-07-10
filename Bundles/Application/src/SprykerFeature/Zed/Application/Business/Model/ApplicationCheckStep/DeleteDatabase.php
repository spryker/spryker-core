<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;

class DeleteDatabase extends AbstractApplicationCheckStep
{

    /**
     * @return bool
     */
    public function run()
    {
        $this->info('Delete database');

        $con = new \PDO(
            'mysql:host='
            . Config::get(SystemConfig::ZED_MYSQL_HOST)
            . ';port=' . Config::get(SystemConfig::ZED_MYSQL_PORT),
            Config::get(SystemConfig::ZED_MYSQL_USERNAME),
            Config::get(SystemConfig::ZED_MYSQL_PASSWORD)
        );

        $q = 'DROP DATABASE IF EXISTS ' . Config::get(SystemConfig::ZED_MYSQL_DATABASE);
        $con->exec($q);
    }

}
