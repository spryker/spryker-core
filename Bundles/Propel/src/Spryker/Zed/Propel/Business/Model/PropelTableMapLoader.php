<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model;

use Spryker\Zed\Propel\PropelConfig;

class PropelTableMapLoader implements PropelTableMapLoaderInterface
{
    /**
     * @see \Propel\Generator\Builder\Om\TableMapLoaderScriptBuilder::FILENAME
     *
     * @var string
     */
    protected const TABLE_MAP_LOADER_SCRIPT_FILENAME = 'loadDatabase.php';

    /**
     * @var \Spryker\Zed\Propel\PropelConfig
     */
    protected $propelConfig;

    /**
     * @param \Spryker\Zed\Propel\PropelConfig $propelConfig
     */
    public function __construct(PropelConfig $propelConfig)
    {
        $this->propelConfig = $propelConfig;
    }

    /**
     * @return bool
     */
    public function loadTableMap(): bool
    {
        $tableMapPath = $this->getTableMapPath();

        if (!file_exists($tableMapPath)) {
            return false;
        }

        require $tableMapPath;

        return true;
    }

    /**
     * @return string
     */
    protected function getTableMapPath(): string
    {
        return $this->propelConfig->getPropelConfig()['paths']['loaderScriptDir'] . DIRECTORY_SEPARATOR . static::TABLE_MAP_LOADER_SCRIPT_FILENAME;
    }
}
