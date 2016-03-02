<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class PropelConfig extends AbstractBundleConfig
{

    const DB_ENGINE_MYSQL = 'mysql';
    const DB_ENGINE_PGSQL = 'pgsql';

    /**
     * @return string
     */
    public function getGeneratedDirectory()
    {
        return APPLICATION_SOURCE_DIR . DIRECTORY_SEPARATOR . 'Generated';
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    public function getSchemaDirectory()
    {
        $config = Config::get(PropelConstants::PROPEL);
        $schemaDir = $config['paths']['schemaDir'] . DIRECTORY_SEPARATOR;

        return $schemaDir;
    }

    /**
     * @return array
     */
    public function getPropelSchemaPathPatterns()
    {
        return [
            Config::get(ApplicationConstants::APPLICATION_SPRYKER_ROOT) . '/*/src/*/Zed/*/Persistence/Propel/Schema/',
        ];
    }

    /**
     * @return string
     */
    public function getLogPath()
    {
        return APPLICATION_ROOT_DIR . '/data/' . Store::getInstance()->getStoreName() . '/logs/ZED/propel.log';
    }

    /**
     * @return string
     */
    public function getCurrentDatabaseEngine()
    {
        return $this->get(ApplicationConstants::ZED_DB_ENGINE);
    }

    /**
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    public function getCurrentDatabaseEngineName()
    {
        $dbEngine = $this->get(ApplicationConstants::ZED_DB_ENGINE);
        $supportedEngines = $this->get(ApplicationConstants::ZED_DB_SUPPORTED_ENGINES);

        if (!array_key_exists($dbEngine, $supportedEngines)) {
            throw new \UnexpectedValueException('Unsupported database engine: ' . $dbEngine);
        }

        return $supportedEngines[$dbEngine];
    }
}
