<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Propel\Module;

use Codeception\Lib\ModuleContainer;
use Codeception\Module\Db;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\Config\Config;

class Database extends Db
{

    /**
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param null $config
     */
    public function __construct(ModuleContainer $moduleContainer, $config)
    {
        $propelConfig = Config::get(PropelConstants::PROPEL);
        $defaultConfig = $propelConfig['database']['connections']['default'];

        $config += [
            'dsn' => $defaultConfig['dsn'],
            'user' => Config::get(PropelConstants::ZED_DB_USERNAME),
            'password' => Config::get(PropelConstants::ZED_DB_PASSWORD)
        ];

        parent::__construct($moduleContainer, $config);
    }


}
