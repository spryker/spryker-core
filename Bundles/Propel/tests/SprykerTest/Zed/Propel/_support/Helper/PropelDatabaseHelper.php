<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Helper;

use Codeception\Module;
use DateTime;
use PDO;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;

class PropelDatabaseHelper extends Module
{
    /**
     * @var string
     */
    public const FIXTURE_TABLE_NAME = 'propel_migration';

    /**
     * @var string
     */
    public const COLUMN_VERSION = 'version';

    /**
     * @var string
     */
    public const COLUMN_EXECUTION_DATETIME = 'execution_datetime';

    /**
     * @var \PDO
     */
    protected static $pdoConnection;

    /**
     * @param array $settings
     *
     * @return void
     */
    public function _beforeSuite($settings = []): void
    {
        parent::_beforeSuite($settings);
    }

    /**
     * @return void
     */
    public function _afterSuite(): void
    {
        parent::_afterSuite();

        static::$pdoConnection = null;
    }

    /**
     * @param int $version
     *
     * @return int
     */
    public function havePropelMigrationPersisted(int $version): int
    {
        $query = sprintf(
            'INSERT INTO %s(%s, %s) VALUES(:version, :execution_datetime)',
            static::FIXTURE_TABLE_NAME,
            static::COLUMN_VERSION,
            static::COLUMN_EXECUTION_DATETIME,
        );

        $this->getConnection()
            ->prepare($query)
            ->execute([
                ':version' => $version,
                ':execution_datetime' => (new DateTime())->format('Y-m-d H:i:s'),
            ]);

        return $version;
    }

    /**
     * @return void
     */
    public function ensurePropelMigrationTableIsEmpty(): void
    {
        $query = sprintf(
            'DELETE FROM %s',
            static::FIXTURE_TABLE_NAME,
        );

        $statement = $this->getConnection()
            ->prepare($query)
            ->execute();
    }

    /**
     * @return \PDO
     */
    protected function getConnection(): PDO
    {
        if (!static::$pdoConnection) {
            static::$pdoConnection = new PDO(
                $this->getDatabaseSourceName(),
                Config::get(PropelConstants::ZED_DB_USERNAME),
                Config::get(PropelConstants::ZED_DB_PASSWORD),
            );
        }

        return static::$pdoConnection;
    }

    /**
     * @return string
     */
    protected function getDatabaseSourceName(): string
    {
        return sprintf(
            '%s:host=%s;port=%s;dbname=%s',
            Config::get(PropelConstants::ZED_DB_ENGINE),
            Config::get(PropelConstants::ZED_DB_HOST),
            Config::get(PropelConstants::ZED_DB_PORT),
            Config::get(PropelConstants::ZED_DB_DATABASE),
        );
    }
}
