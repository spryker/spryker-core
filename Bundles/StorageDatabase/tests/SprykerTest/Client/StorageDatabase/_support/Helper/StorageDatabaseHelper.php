<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\StorageDatabase\Helper;

use Codeception\Module;
use PDO;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\StorageDatabase\StorageDatabaseConstants;
use Spryker\Zed\Propel\PropelConfig;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;

class StorageDatabaseHelper extends Module
{
    use ConfigHelperTrait;

    /**
     * @var string
     */
    public const FIXTURE_TABLE_NAME = 'spy_test_fixture_storage';

    /**
     * @var string
     */
    public const COLUMN_DATA = 'data';

    /**
     * @var string
     */
    public const COLUMN_KEY = 'key';

    /**
     * @var string
     */
    public const COLUMN_ALIAS_KEYS = 'alias_keys';

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

        $this->getConfigHelper()->mockEnvironmentConfig(StorageDatabaseConstants::DB_HOST, Config::get(PropelConstants::ZED_DB_HOST));
        $this->getConfigHelper()->mockEnvironmentConfig(StorageDatabaseConstants::DB_PORT, Config::get(PropelConstants::ZED_DB_PORT));
        $this->getConfigHelper()->mockEnvironmentConfig(StorageDatabaseConstants::DB_DATABASE, Config::get(PropelConstants::ZED_DB_DATABASE));
        $this->getConfigHelper()->mockEnvironmentConfig(StorageDatabaseConstants::DB_USERNAME, Config::get(PropelConstants::ZED_DB_USERNAME));
        $this->getConfigHelper()->mockEnvironmentConfig(StorageDatabaseConstants::DB_PASSWORD, Config::get(PropelConstants::ZED_DB_PASSWORD));
        $this->getConfigHelper()->mockEnvironmentConfig(StorageDatabaseConstants::DB_ENGINE, Config::get(PropelConstants::ZED_DB_ENGINE));

        $this->createFixtureStorageTable();
    }

    /**
     * @return void
     */
    public function _afterSuite(): void
    {
        parent::_afterSuite();

        $this->dropFixtureStorageTable();
        static::$pdoConnection = null;
    }

    /**
     * @param string $key
     * @param array<string, mixed> $data
     * @param array $aliasKeys
     *
     * @return bool
     */
    public function haveRecordInFixtureStorageTable(string $key, array $data, array $aliasKeys): bool
    {
        $query = sprintf(
            'INSERT INTO %s(%s, %s, %s) VALUES(:data_value, :key_value, :alias_keys_value)',
            static::FIXTURE_TABLE_NAME,
            static::COLUMN_DATA,
            $this->getEscapedFieldName(static::COLUMN_KEY),
            static::COLUMN_ALIAS_KEYS,
        );
        $statement = $this->getConnection()->prepare($query);

        return $statement->execute([
            ':data_value' => $this->encodeJson($data),
            ':key_value' => $key,
            ':alias_keys_value' => $this->encodeJson($aliasKeys),
        ]);
    }

    /**
     * @return void
     */
    protected function createFixtureStorageTable(): void
    {
        $query = sprintf(
            'CREATE TABLE IF NOT EXISTS %s(
                %s TEXT NOT NULL,
                %s VARCHAR(255) NOT NULL,
                %s TEXT not null
            );',
            static::FIXTURE_TABLE_NAME,
            static::COLUMN_DATA,
            $this->getEscapedFieldName(static::COLUMN_KEY),
            static::COLUMN_ALIAS_KEYS,
        );

        $this->getConnection()->exec($query);
    }

    /**
     * @return void
     */
    protected function dropFixtureStorageTable(): void
    {
        $query = sprintf('DROP TABLE IF EXISTS %s', static::FIXTURE_TABLE_NAME);

        $this->getConnection()->exec($query);
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

    /**
     * @param array $value
     *
     * @return string
     */
    public function encodeJson(array $value): string
    {
        return (new UtilEncodingService())->encodeJson($value) ?? '';
    }

    /**
     * @param string $value
     *
     * @return array<mixed>|null
     */
    public function decodeJson(string $value): ?array
    {
        return (new UtilEncodingService())->decodeJson($value, true);
    }

    /**
     * @param string $fieldName
     *
     * @return string
     */
    protected function getEscapedFieldName(string $fieldName): string
    {
        if (Config::get(PropelConstants::ZED_DB_ENGINE) == PropelConfig::DB_ENGINE_MYSQL) {
            return sprintf('`%s`', $fieldName);
        }

        return $fieldName;
    }
}
