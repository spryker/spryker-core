<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Storage\Reader;

use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Connection\StatementInterface;
use Spryker\Client\StorageDatabase\Connection\ConnectionProviderInterface;
use Spryker\Client\StorageDatabase\Exception\StatementNotPreparedException;
use Spryker\Client\StorageDatabase\StorageTableNameResolver\StorageTableNameResolverInterface;

abstract class AbstractStorageReader implements StorageReaderInterface
{
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
        $statement = $this->createSingleSelectStatementForResourceKey($resourceKey);
        $statement->execute();

        return $this->fetchSingleResult($statement);
    }

    /**
     * @param array $resourceKeys
     *
     * @return array
     */
    public function getMulti(array $resourceKeys): array
    {
        $statement = $this->createMultiSelectStatementForResourceKeys($resourceKeys);
        $statement->execute();

        return $this->fetchMultiResults($statement, $resourceKeys);
    }

    /**
     * @param string $sqlString
     *
     * @throws \Spryker\Client\StorageDatabase\Exception\StatementNotPreparedException
     *
     * @return \Propel\Runtime\Connection\StatementInterface
     */
    protected function createPreparedStatement(string $sqlString): StatementInterface
    {
        $statement = $this->getConnection()->prepare($sqlString);

        if (!$statement) {
            throw new StatementNotPreparedException('Failed to prepare statement object for selecting storage data.');
        }

        return $statement;
    }

    /**
     * @param \Propel\Runtime\Connection\StatementInterface $statement
     *
     * @return string
     */
    protected function fetchSingleResult(StatementInterface $statement): string
    {
        $result = $statement->fetch();

        return $result['resource_data'] ?? '';
    }

    /**
     * @param \Propel\Runtime\Connection\StatementInterface $statement
     * @param string[] $resourceKeys
     *
     * @return array
     */
    protected function fetchMultiResults(StatementInterface $statement, array $resourceKeys): array
    {
        $results = $statement->fetchAll();
        $formattedResults = [];

        if (!$results) {
            return $formattedResults;
        }

        foreach ($results as $result) {
            $resourceKey = $result['resource_key'] ?? null;
            $resourceData = $result['resource_data'] ?? null;

            if (!$resourceKey || !$resourceData) {
                continue;
            }

            $formattedResults[$resourceKey] = $resourceData;
        }

        return $formattedResults;
    }

    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    protected function getConnection(): ConnectionInterface
    {
        return $this->connectionProvider->getConnection();
    }

    /**
     * @param string $resourceKey
     *
     * @return \Propel\Runtime\Connection\StatementInterface
     */
    abstract protected function createSingleSelectStatementForResourceKey(string $resourceKey): StatementInterface;

    /**
     * @param array $resourceKeys
     *
     * @return \Propel\Runtime\Connection\StatementInterface
     */
    abstract protected function createMultiSelectStatementForResourceKeys(array $resourceKeys): StatementInterface;
}
