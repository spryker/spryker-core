<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Storage\Reader;

use Propel\Runtime\Connection\StatementInterface;

class MySqlStorageReader extends AbstractStorageReader
{
    protected const DEFAULT_PLACEHOLDER_KEY = ':key';
    protected const DEFAULT_PLACEHOLDER_ALIAS_KEY = ':alias_key';

    protected const SELECT_STATEMENT_PATTERN = '
      SELECT %1$s as resource_key, (CASE WHEN `key` = %1$s THEN data WHEN JSON_EXTRACT(alias_keys, %2$s) IS NOT NULL THEN JSON_EXTRACT(alias_keys, %2$s) END) AS resource_data
        FROM %3$s
        HAVING resource_data IS NOT NULL
    ';

    /**
     * @param string $resourceKey
     *
     * @return \Propel\Runtime\Connection\StatementInterface
     */
    protected function createSingleSelectStatementForResourceKey(string $resourceKey): StatementInterface
    {
        $tableName = $this->tableNameResolver->resolveByResourceKey($resourceKey);
        $selectSqlString = $this->buildSelectQuerySql($tableName);
        $statement = $this->createPreparedStatement($selectSqlString);
        $statement->bindValue(static::DEFAULT_PLACEHOLDER_KEY, $resourceKey);
        $statement->bindValue(static::DEFAULT_PLACEHOLDER_ALIAS_KEY, $this->formatKeyAsMySqlSearchPath($resourceKey));

        return $statement;
    }

    /**
     * @param array $resourceKeys
     *
     * @return \Propel\Runtime\Connection\StatementInterface
     */
    protected function createMultiSelectStatementForResourceKeys(array $resourceKeys): StatementInterface
    {
        $queryDataPerTable = $this->prepareMultiTableQueryData($resourceKeys);
        $statement = $this->buildMultiTableSelectStatement($queryDataPerTable);

        return $this->bindValuesToStatement($statement, $queryDataPerTable);
    }

    /**
     * @param string[] $resourceKeys
     *
     * @return array
     */
    protected function prepareMultiTableQueryData(array $resourceKeys): array
    {
        $multiTableQueryData = [];

        foreach ($resourceKeys as $index => $resourceKey) {
            $tableName = $this->tableNameResolver->resolveByResourceKey($resourceKey);
            $keyPlaceholder = $this->buildKeyPlaceholder($index);
            $aliasKeyPlaceholder = $this->buildAliasKeysPlaceholder($index);
            $multiTableQueryData[$tableName][] = [
                $keyPlaceholder => $resourceKey,
                $aliasKeyPlaceholder => $this->formatKeyAsMySqlSearchPath($resourceKey),
            ];
        }

        return $multiTableQueryData;
    }

    /**
     * @param array $queryDataPerTable
     *
     * @return \Propel\Runtime\Connection\StatementInterface
     */
    protected function buildMultiTableSelectStatement(array $queryDataPerTable): StatementInterface
    {
        $selectFragments = [];

        foreach ($queryDataPerTable as $tableName => $tableQueryData) {
            foreach ($tableQueryData as $dataSet) {
                [$keyPlaceholder, $aliasKeyPlaceholder] = array_keys($dataSet);
                $selectFragments[] = $this->buildSelectQuerySql($tableName, $keyPlaceholder, $aliasKeyPlaceholder);
            }
        }

        $selectSqlString = implode(' UNION ', $selectFragments);

        return $this->createPreparedStatement($selectSqlString);
    }

    /**
     * @param \Propel\Runtime\Connection\StatementInterface $statement
     * @param string[][][] $queryDataPerTable
     *
     * @return \Propel\Runtime\Connection\StatementInterface
     */
    protected function bindValuesToStatement(
        StatementInterface $statement,
        array $queryDataPerTable
    ): StatementInterface {
        foreach ($queryDataPerTable as $queryData) {
            foreach (array_merge(...$queryData) as $placeholder => $value) {
                $statement->bindValue($placeholder, $value);
            }
        }

        return $statement;
    }

    /**
     * @param string $tableName
     * @param string $keyPlaceholder
     * @param string $aliasKeyPlaceholder
     *
     * @return string
     */
    protected function buildSelectQuerySql(
        string $tableName,
        string $keyPlaceholder = self::DEFAULT_PLACEHOLDER_KEY,
        string $aliasKeyPlaceholder = self::DEFAULT_PLACEHOLDER_ALIAS_KEY
    ): string {
        return sprintf(
            static::SELECT_STATEMENT_PATTERN,
            $keyPlaceholder,
            $aliasKeyPlaceholder,
            $tableName
        );
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function formatKeyAsMySqlSearchPath(string $key): string
    {
        return sprintf('$."%s"', $key);
    }

    /**
     * @param int $index
     *
     * @return string
     */
    protected function buildKeyPlaceholder(int $index = 0): string
    {
        return static::DEFAULT_PLACEHOLDER_KEY . $index;
    }

    /**
     * @param int $index
     *
     * @return string
     */
    protected function buildAliasKeysPlaceholder(int $index = 0): string
    {
        return static::DEFAULT_PLACEHOLDER_ALIAS_KEY . $index;
    }
}
