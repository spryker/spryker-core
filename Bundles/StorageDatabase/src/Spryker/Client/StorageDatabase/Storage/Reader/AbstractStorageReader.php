<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Storage\Reader;

use PDOStatement;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Client\StorageDatabase\Connection\ConnectionProviderInterface;
use Spryker\Client\StorageDatabase\Exception\StatementNotPreparedException;
use Spryker\Client\StorageDatabase\StorageTableNameResolver\StorageTableNameResolverInterface;
use Spryker\Client\StorageDatabaseExtension\Storage\Reader\StorageReaderInterface;

abstract class AbstractStorageReader implements StorageReaderInterface
{
    protected const SELECT_STATEMENT_PATTERN = 'SELECT %s FROM %s WHERE %s OR %s';

    protected const FIELD_DATA = 'data';
    protected const FIELD_KEY = 'key';
    protected const FIELD_ALIAS_KEYS = 'alias_keys';

    /**
     * @var \Spryker\Client\StorageDatabase\Connection\ConnectionProviderInterface
     */
    protected $connectionProvider;

    /**
     * @var \Spryker\Client\StorageDatabase\StorageTableNameResolver\StorageTableNameResolverInterface
     */
    protected $tableNameResolver;

    /**
     * @param \Spryker\Client\StorageDatabase\Connection\ConnectionProviderInterface $connectionProvider
     * @param \Spryker\Client\StorageDatabase\StorageTableNameResolver\StorageTableNameResolverInterface $tableNameResolver
     */
    public function __construct(ConnectionProviderInterface $connectionProvider, StorageTableNameResolverInterface $tableNameResolver)
    {
        $this->connectionProvider = $connectionProvider;
        $this->tableNameResolver = $tableNameResolver;
    }

    /**
     * @param string $resourceKey
     *
     * @return string
     */
    public function get(string $resourceKey): string
    {
        $tableName = $this->tableNameResolver->resolveByResourceKey($resourceKey);
        $selectSqlString = $this->buildSingleCriterionQuerySql($tableName);
        $statement = $this->createPreparedStatement($selectSqlString);

        $statement->execute([
            $resourceKey,
            $this->transformToJsonString($resourceKey),
        ]);

        return $this->fetchSingleResult($statement);
    }

    /**
     * @param array $resourceKeys
     *
     * @return array
     */
    public function getMulti(array $resourceKeys): array
    {
        $queryDataPerTable = $this->prepareMultiTableQueryData($resourceKeys);
        $queryInputData = $this->prepareMultiTableQueryInputData($queryDataPerTable);
        $statement = $this->buildMultiTableSelectStatement($queryDataPerTable);

        $statement->execute($queryInputData);

        return $this->fetchMultiResults($statement, $resourceKeys);
    }

    /**
     * @param array $queryDataPerTable
     *
     * @return \PDOStatement
     */
    protected function buildMultiTableSelectStatement(array $queryDataPerTable): PDOStatement
    {
        $selectFragments = [];

        foreach ($queryDataPerTable as $tableName => $tableQueryData) {
            $keyQueryData = $this->getKeyQueryDataFromTableQueryData($tableQueryData);
            $aliasKeysQueryData = $this->getAliasKeysQueryDataFromTableQueryData($tableQueryData);
            $selectFragments[] = $this->getSqlSelectFragment($tableName, array_keys($keyQueryData), array_keys($aliasKeysQueryData));
        }

        $selectSqlString = implode(' UNION ', $selectFragments);

        return $this->createPreparedStatement($selectSqlString);
    }

    /**
     * @param array $tableQueryData
     *
     * @return array
     */
    protected function getKeyQueryDataFromTableQueryData(array $tableQueryData): array
    {
        return $tableQueryData[static::FIELD_KEY] ?? [];
    }

    /**
     * @param array $tableQueryData
     *
     * @return array
     */
    protected function getAliasKeysQueryDataFromTableQueryData(array $tableQueryData): array
    {
        return $tableQueryData[static::FIELD_ALIAS_KEYS] ?? [];
    }

    /**
     * @param array $queryDataPerTable
     *
     * @return string[]
     */
    protected function prepareMultiTableQueryInputData(array $queryDataPerTable): array
    {
        $multiTableQueryInputData = [];

        foreach ($queryDataPerTable as $tableQueryData) {
            foreach ($tableQueryData as $fieldQueryData) {
                $multiTableQueryInputData = array_merge($multiTableQueryInputData, $fieldQueryData);
            }
        }

        return $multiTableQueryInputData;
    }

    /**
     * @param string $sqlString
     *
     * @throws \Spryker\Client\StorageDatabase\Exception\StatementNotPreparedException
     *
     * @return \PDOStatement
     */
    protected function createPreparedStatement(string $sqlString): PDOStatement
    {
        $statement = $this->getConnection()->prepare($sqlString);

        if (!$statement) {
            throw new StatementNotPreparedException('Failed to prepare statement object for selecting storage data.');
        }

        return $statement;
    }

    /**
     * @param \PDOStatement $statement
     *
     * @return string
     */
    protected function fetchSingleResult(PDOStatement $statement): string
    {
        $result = $statement->fetch();

        return $result[static::FIELD_DATA] ?? '';
    }

    /**
     * @param \PDOStatement $statement
     * @param string[] $resourceKeys
     *
     * @return array
     */
    protected function fetchMultiResults(PDOStatement $statement, array $resourceKeys): array
    {
        $results = $statement->fetchAll();

        if (!$results) {
            $results = $this->createEmptyMultiResults($resourceKeys);
        }

        return array_map(function ($result) {
            return $result[static::FIELD_DATA] ?? null;
        }, $results);
    }

    /**
     * @param string[] $resourceKeys
     *
     * @return array
     */
    protected function createEmptyMultiResults(array $resourceKeys): array
    {
        return array_fill_keys($resourceKeys, null);
    }

    /**
     * @param string $tableName
     * @param string[] $keyPlaceholders
     * @param string[] $aliasKeysPlaceholders
     *
     * @return string
     */
    protected function getSqlSelectFragment(string $tableName, array $keyPlaceholders, array $aliasKeysPlaceholders): string
    {
        if ($this->isSingleCriterionQuery($keyPlaceholders)) {
            return $this->buildSingleCriterionQuerySql($tableName, current($keyPlaceholders), current($aliasKeysPlaceholders));
        }

        return $this->buildMultiCriteriaQuerySql($tableName, $keyPlaceholders, $aliasKeysPlaceholders);
    }

    /**
     * @param string[] $keyPlaceholders
     *
     * @return bool
     */
    protected function isSingleCriterionQuery(array $keyPlaceholders): bool
    {
        return count($keyPlaceholders) === 1;
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
            $aliasKeysPlaceholder = $this->buildAliasKeysPlaceholder($index);
            $multiTableQueryData[$tableName][static::FIELD_KEY][$keyPlaceholder] = $resourceKey;
            $multiTableQueryData[$tableName][static::FIELD_ALIAS_KEYS][$aliasKeysPlaceholder] = $this->transformToJsonString($resourceKey);
        }

        return $multiTableQueryData;
    }

    /**
     * @param string $tableName
     * @param string $keyPlaceholder
     * @param string $aliasKeysPlaceholder
     *
     * @return string
     */
    protected function buildSingleCriterionQuerySql(string $tableName, string $keyPlaceholder = '?', string $aliasKeysPlaceholder = '?'): string
    {
        return sprintf(
            static::SELECT_STATEMENT_PATTERN,
            static::FIELD_DATA,
            $tableName,
            $this->buildKeyEqualsValuePredicateFragment($keyPlaceholder),
            $this->buildValueInAliasKeysPredicateFragment($aliasKeysPlaceholder)
        );
    }

    /**
     * @param string $tableName
     * @param string[] $keyPlaceholders
     * @param array $aliasKeysPlaceholders
     *
     * @return string
     */
    protected function buildMultiCriteriaQuerySql(string $tableName, array $keyPlaceholders, array $aliasKeysPlaceholders): string
    {
        return sprintf(
            static::SELECT_STATEMENT_PATTERN,
            static::FIELD_DATA,
            $tableName,
            $this->buildKeyInValuesPredicateFragment($keyPlaceholders),
            $this->buildAliasKeysAndValuesIntersectionPredicateFragment($aliasKeysPlaceholders)
        );
    }

    /**
     * @param string $keyPlaceholder
     *
     * @return string
     */
    abstract protected function buildKeyEqualsValuePredicateFragment(string $keyPlaceholder): string;

    /**
     * @param string[] $keyPlaceholders
     *
     * @return string
     */
    abstract protected function buildKeyInValuesPredicateFragment(array $keyPlaceholders): string;

    /**
     * @param array $keyPlaceholders
     *
     * @return string
     */
    protected function buildAliasKeysAndValuesIntersectionPredicateFragment(array $keyPlaceholders): string
    {
        $aliasKeysPredicates = [];

        foreach ($keyPlaceholders as $keyPlaceholder) {
            $aliasKeysPredicates[] = $this->buildValueInAliasKeysPredicateFragment($keyPlaceholder);
        }

        return sprintf('(%s)', implode(' OR ', $aliasKeysPredicates));
    }

    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    protected function getConnection(): ConnectionInterface
    {
        return $this->connectionProvider->getConnection();
    }

    /**
     * @param int $index
     *
     * @return string
     */
    protected function buildKeyPlaceholder(int $index = 0): string
    {
        return sprintf(':%s%d', static::FIELD_KEY, $index);
    }

    /**
     * @param int $index
     *
     * @return string
     */
    protected function buildAliasKeysPlaceholder(int $index = 0): string
    {
        return sprintf(':%s%d', static::FIELD_ALIAS_KEYS, $index);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function transformToJsonString(string $value): string
    {
        return sprintf('"%s"', $value);
    }

    /**
     * @param string $keyPlaceholder
     *
     * @return string
     */
    abstract protected function buildValueInAliasKeysPredicateFragment(string $keyPlaceholder): string;
}
