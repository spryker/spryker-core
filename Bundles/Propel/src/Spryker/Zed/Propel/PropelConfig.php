<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Propel\Business\Exception\UnSupportedDatabaseEngineException;

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
     * @return array
     */
    public function getPropelConfig()
    {
        return $this->get(PropelConstants::PROPEL);
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    public function getSchemaDirectory()
    {
        $config = $this->getPropelConfig();
        $schemaDir = $config['paths']['schemaDir'] . DIRECTORY_SEPARATOR;

        return $schemaDir;
    }

    /**
     * @return array
     */
    public function getPropelSchemaPathPatterns()
    {
        return [
            $this->get(ApplicationConstants::APPLICATION_SPRYKER_ROOT) . '/*/src/*/Zed/*/Persistence/Propel/Schema/',
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
        return $this->get(PropelConstants::ZED_DB_ENGINE);
    }

    /**
     * @throws \Spryker\Zed\Propel\Business\Exception\UnSupportedDatabaseEngineException
     *
     * @return string
     */
    public function getCurrentDatabaseEngineName()
    {
        $dbEngine = $this->getCurrentDatabaseEngine();
        $supportedEngines = $this->get(PropelConstants::ZED_DB_SUPPORTED_ENGINES);

        if (!array_key_exists($dbEngine, $supportedEngines)) {
            throw new UnSupportedDatabaseEngineException('Unsupported database engine: ' . $dbEngine);
        }

        return $supportedEngines[$dbEngine];
    }

}
