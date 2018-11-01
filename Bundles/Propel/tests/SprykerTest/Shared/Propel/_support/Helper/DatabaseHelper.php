<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Propel\Helper;

use Codeception\Lib\ModuleContainer;
use Codeception\Module\Db;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;

class DatabaseHelper extends Db
{
    /**
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param array|null $config
     */
    public function __construct(ModuleContainer $moduleContainer, $config)
    {
        $propelConfig = Config::get(PropelConstants::PROPEL);
        $defaultConfig = $propelConfig['database']['connections']['default'];

        $config += [
            'dsn' => $defaultConfig['dsn'],
            'user' => Config::get(PropelConstants::ZED_DB_USERNAME),
            'password' => Config::get(PropelConstants::ZED_DB_PASSWORD),
        ];

        parent::__construct($moduleContainer, $config);
    }
}
