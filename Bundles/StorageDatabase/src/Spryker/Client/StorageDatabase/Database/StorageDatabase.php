<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Database;

use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Client\StorageDatabase\ConnectionProvider\ConnectionProviderInterface;
use Spryker\Client\StorageDatabase\ResourceToTableMapper\ResourceKeyToTableNameResolverInterface;

class StorageDatabase implements StorageDatabaseInterface
{
    protected const FIELD_DATA = 'data';

    protected const FIELD_KEY = 'key';

    protected const PATTERN_SELECT_SINGLE_RESULT = 'SELECT %s FROM %s WHERE %s = :key LIMIT 1';

    protected const PATTERN_SELECT_MULTI_RESULT = 'SELECT %s FROM %s WHERE %s IN (%s)';

    /**
     * @var \Spryker\Client\StorageDatabase\ConnectionProvider\ConnectionProviderInterface
     */
    protected $connectionProvider;

    /**
     * @var \Spryker\Client\StorageDatabase\ResourceToTableMapper\ResourceKeyToTableNameResolverInterface
     */
    protected $resourceToTableMapper;

    /**
     * @var array
     */
    protected $accessStats = [
        'count' => [
            'read' => 0,
        ],
        'keys' => [
            'read' => [],
        ],
    ];

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @param \Spryker\Client\StorageDatabase\ConnectionProvider\ConnectionProviderInterface $connectionProvider
     * @param \Spryker\Client\StorageDatabase\ResourceToTableMapper\ResourceKeyToTableNameResolverInterface $resourceToTableMapper
     */
    public function __construct(ConnectionProviderInterface $connectionProvider, ResourceKeyToTableNameResolverInterface $resourceToTableMapper)
    {
        $this->connectionProvider = $connectionProvider;
        $this->resourceToTableMapper = $resourceToTableMapper;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        $result = $this->fetchSingleResultForKey($key);

        $this->addReadAccessStats($key);

        return $this->decodeResult($result);
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys): array
    {
        if (count($keys) === 0) {
            return [];
        }

        $decodedResults = [];
        $results = $this->fetchMultiResultForKeys($keys);

        foreach ($results as $result) {
            $decodedResults[] = $this->decodeResult($result);
        }

        $this->addMultiReadAccessStats($keys);

        return $decodedResults;
    }

    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    protected function getConnection(): ConnectionInterface
    {
        return $this->connectionProvider->getConnection();
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function fetchSingleResultForKey(string $key): string
    {
        $tableName = $this->resourceToTableMapper->resolve($key);
        $sqlString = sprintf(static::PATTERN_SELECT_SINGLE_RESULT, static::FIELD_DATA, $tableName, static::FIELD_KEY);
        $statement = $this->getConnection()->prepare($sqlString);
        $statement->bindValue(':key', $key);
        $statement->execute();
        $result = $statement->fetch();

        return $result[static::FIELD_DATA] ?? '';
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    protected function fetchMultiResultForKeys(array $keys): array
    {
        $keysPerTable = $this->prepareMultiSelectQueryData($keys);
        $placeholderToValueMap = array_merge(...array_values($keysPerTable));
        $unionSelectString = $this->buildUnionSelectQuery($keysPerTable);

        $statement = $this->getConnection()->prepare($unionSelectString);
        $statement->execute($placeholderToValueMap);

        $results = $statement->fetchAll();

        return array_map(function (array $result) {
            return $result[static::FIELD_DATA] ?? '';
        }, $results);
    }

    /**
     * @param array $keysPerTable
     *
     * @return string
     */
    protected function buildUnionSelectQuery(array $keysPerTable): string
    {
        $tableSelects = [];

        foreach ($keysPerTable as $tableName => $criteriaKeys) {
            $keyInCriterion = implode(', ', array_keys($criteriaKeys));
            $selectString = sprintf(static::PATTERN_SELECT_MULTI_RESULT, static::FIELD_DATA, $tableName, static::FIELD_KEY, $keyInCriterion);
            $tableSelects[] = $selectString;
        }

        return implode(' UNION ', $tableSelects);
    }

    /**
     * @param string[] $keys
     *
     * @return array
     */
    public function prepareMultiSelectQueryData(array $keys): array
    {
        $result = [];

        foreach ($keys as $index => $key) {
            $tableName = $this->resourceToTableMapper->resolve($key);
            $placeholder = sprintf(':%s%d', static::FIELD_KEY, $index);
            $result[$tableName][$placeholder] = $key;
        }

        return $result;
    }

    /**
     * @param string $data
     *
     * @return mixed|string
     */
    protected function decodeResult(string $data)
    {
        $decodedResult = json_decode($data, true);

        if (json_last_error() === JSON_ERROR_SYNTAX) {
            return $data;
        }

        return $decodedResult;
    }

    /**
     * @return void
     */
    public function resetAccessStats(): void
    {
        $this->accessStats = [
            'count' => [
                'read' => 0,
            ],
            'keys' => [
                'read' => [],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getAccessStats(): array
    {
        return $this->accessStats;
    }

    /**
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    /**
     * @param string $key
     *
     * @return void
     */
    protected function addReadAccessStats(string $key): void
    {
        if ($this->debug) {
            $this->accessStats['count']['read']++;
            $this->accessStats['keys']['read'][] = $key;
        }
    }

    /**
     * @param string[] $keys
     *
     * @return void
     */
    protected function addMultiReadAccessStats(array $keys): void
    {
        if ($this->debug) {
            $this->accessStats['count']['read'] += count($keys);
            $this->accessStats['keys']['read'] = array_unique(
                array_merge($this->accessStats['keys']['read'], $keys)
            );
        }
    }
}
