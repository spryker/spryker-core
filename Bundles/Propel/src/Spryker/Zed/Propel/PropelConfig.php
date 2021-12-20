<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel;

use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Propel\Business\Exception\UnSupportedDatabaseEngineException;
use Spryker\Zed\PropelOrm\Business\Builder\ExtensionObjectBuilder;
use Spryker\Zed\PropelOrm\Business\Builder\ExtensionQueryBuilder;
use Spryker\Zed\PropelOrm\Business\Builder\ObjectBuilder;
use Spryker\Zed\PropelOrm\Business\Builder\QueryBuilder;

class PropelConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const DB_ENGINE_MYSQL = 'mysql';

    /**
     * @var string
     */
    public const DB_ENGINE_PGSQL = 'pgsql';

    /**
     * @var int
     */
    protected const PROCESS_TIMEOUT = 600;

    /**
     * Specification:
     * - This also applies to all identifiers in Postgres such as table names, fields, etc.
     * - Postgres will truncate everyhing beyond this limit.
     * - It can be modified by editing the sourcecode of postgres, which is not advised.
     *
     * @api
     *
     * @var int
     */
    public const POSTGRES_INDEX_NAME_MAX_LENGTH = 63;

    /**
     * @api
     *
     * @return string
     */
    public function getGeneratedDirectory()
    {
        return APPLICATION_SOURCE_DIR . DIRECTORY_SEPARATOR . 'Generated';
    }

    /**
     * Specification:
     * - This is the Spryker default propel config.
     * - Please use `config_propel.php` to set environment specific values.
     *
     * @api
     *
     * @return array<string, mixed>
     */
    public function getDefaultPropelConfig(): array
    {
        return [
            'database' => [
                'connections' => [],
            ],
            'runtime' => [
                'defaultConnection' => 'default',
                'connections' => ['default', 'zed'],
            ],
            'generator' => [
                'defaultConnection' => 'default',
                'connections' => ['default', 'zed'],
                'objectModel' => [
                    'defaultKeyType' => 'fieldName',
                    'builders' => [
                        // If you need full entity logging on Create/Update/Delete, then switch to
                        // Spryker\Zed\PropelOrm\Business\Builder\ObjectBuilderWithLogger instead.
                        'object' => ObjectBuilder::class,
                        'objectstub' => ExtensionObjectBuilder::class,
                        'query' => QueryBuilder::class,
                        'querystub' => ExtensionQueryBuilder::class,
                    ],
                ],
            ],
            'paths' => [
                'phpDir' => APPLICATION_ROOT_DIR,
                'sqlDir' => APPLICATION_ROOT_DIR . '/src/Orm/Propel/Sql/',
                'migrationDir' => APPLICATION_ROOT_DIR . '/src/Orm/Propel/Migration_' . $this->getCurrentDatabaseEngine() . '/',
                'schemaDir' => APPLICATION_ROOT_DIR . '/src/Orm/Propel/Schema/',
                'loaderScriptDir' => APPLICATION_ROOT_DIR . '/data/cache/propel/generated-conf/',
            ],
        ];
    }

    /**
     * @api
     *
     * @return array<mixed>
     */
    public function getPropelConfig()
    {
        return array_replace_recursive($this->getDefaultPropelConfig(), $this->get(PropelConstants::PROPEL, []));
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->get(PropelConstants::ZED_DB_USERNAME);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->get(PropelConstants::ZED_DB_PASSWORD);
    }

    /**
     * @api
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
     * @api
     *
     * @return string
     */
    public function getMigrationDirectory()
    {
        $config = $this->getPropelConfig();
        $schemaDir = $config['paths']['migrationDir'] . DIRECTORY_SEPARATOR;

        return $schemaDir;
    }

    /**
     * First load the core file if present and then override it with the one from project
     *
     * @api
     *
     * @return array<string>
     */
    public function getPropelSchemaPathPatterns()
    {
        return array_unique(array_merge(
            $this->getCorePropelSchemaPathPatterns(),
            $this->getProjectPropelSchemaPathPatterns(),
        ));
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getCorePropelSchemaPathPatterns()
    {
        return [APPLICATION_VENDOR_DIR . '/*/*/src/*/Zed/*/Persistence/Propel/Schema/'];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getProjectPropelSchemaPathPatterns()
    {
        return [APPLICATION_SOURCE_DIR . '/*/Zed/*/Persistence/Propel/Schema/'];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getLogPath()
    {
        $basePath = APPLICATION_ROOT_DIR . '/data/logs/';

        if (!is_writable($basePath)) {
            $basePath = $this->getBCBaseLogPath();
        }

        $defaultPath = $basePath . 'ZED/propel.log';

        return $this->get(PropelConstants::LOG_FILE_PATH, $defaultPath);
    }

    /**
     * @deprecated Exists for BC reasons.
     *
     * @return string
     */
    protected function getBCBaseLogPath(): string
    {
        return APPLICATION_ROOT_DIR . '/data/' . APPLICATION_STORE . '/logs/';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCurrentDatabaseEngine()
    {
        return $this->get(PropelConstants::ZED_DB_ENGINE);
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\UnSupportedDatabaseEngineException
     *
     * @return string
     */
    public function getCurrentDatabaseEngineName()
    {
        $dbEngine = $this->getCurrentDatabaseEngine();
        $supportedEngines = $this->get(PropelConstants::ZED_DB_SUPPORTED_ENGINES, [
            static::DB_ENGINE_MYSQL => 'MySql',
            static::DB_ENGINE_PGSQL => 'PostgreSql',
        ]);

        if (!array_key_exists($dbEngine, $supportedEngines)) {
            throw new UnSupportedDatabaseEngineException('Unsupported database engine: ' . $dbEngine);
        }

        return $supportedEngines[$dbEngine];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getWhitelistForAllowedAttributeValueChanges()
    {
        return [];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getTableElementHierarchy(): array
    {
        return [
            'column',
            'foreign-key',
            'index',
            'unique',
            'id-method-parameter',
            'behavior',
        ];
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isDebugEnabled(): bool
    {
        return $this->get(PropelConstants::PROPEL_DEBUG, false);
    }

    /**
     * Specification:
     * - Returns the value for the process timeout in seconds, after which an exception will be thrown.
     * - Can return int, float or null to disable timeout.
     *
     * @api
     *
     * @return float|int|null
     */
    public function getProcessTimeout()
    {
        return static::PROCESS_TIMEOUT;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function allowIndexOverriding(): bool
    {
        return false;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCurrentZedDatabaseName(): string
    {
        return $this->get(PropelConstants::ZED_DB_DATABASE);
    }
}
