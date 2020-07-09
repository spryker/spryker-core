<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Persistence\BatchProcessor;

use DateTime;
use Exception;
use PDO;
use PDOStatement;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Adapter\AdapterInterface;
use Propel\Runtime\Adapter\Pdo\PgsqlAdapter;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\ColumnMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\Util\PropelDateTime;
use Spryker\Zed\Propel\Exception\StatementNotPreparedException;
use Throwable;

/**
 * This trait is not capable to do insert/update of related entities.
 * P&S is not triggered while using this trait.
 */
trait ActiveRecordBatchProcessorTrait
{
    /**
     * @phpstan-var array<string, array<int, \Propel\Runtime\ActiveRecord\ActiveRecordInterface>>
     *
     * @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface[][]
     */
    protected $entitiesToInsert = [];

    /**
     * @phpstan-var array<string, array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>>
     *
     * @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface[][]
     */
    protected $entitiesToUpdate = [];

    /**
     * @var \Propel\Runtime\Map\TableMap[]
     */
    protected $tableMapClasses = [];

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $writeConnections;

    /**
     * @var \Propel\Runtime\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \DateTime
     */
    protected $highPrecisionDateTime;

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return void
     */
    public function persist(ActiveRecordInterface $entity): void
    {
        if (!$entity->isModified()) {
            return;
        }

        $storageName = $entity->isNew() ? 'entitiesToInsert' : 'entitiesToUpdate';

        $className = get_class($entity);

        if (!isset($this->{$storageName}[$className])) {
            $this->{$storageName}[$className] = [];
        }

        $this->{$storageName}[$className][] = $entity;
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        $this->insertEntities($this->entitiesToInsert);
        $this->updateEntities($this->entitiesToUpdate);

        return true;
    }

    /**
     * @phpstan-param array<string, array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>>
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface[][] $entitiesToInsert
     *
     * @return void
     */
    protected function insertEntities(array $entitiesToInsert): void
    {
        foreach ($entitiesToInsert as $entityClassName => $entities) {
            $connection = $this->getWriteConnection($entityClassName);

            $entities = $this->preSave($entities, $connection);
            $entities = $this->preInsert($entities, $connection);
            $statement = $this->buildInsertStatement($entityClassName, $entities);
            $this->executeStatement($statement, $entityClassName, 'insert');
            $this->postInsert($entities, $connection);
            $this->postSave($entities, $connection);
        }
    }

    /**
     * @phpstan-param array<string, array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>>
     *
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface[][] $entitiesToUpdate
     *
     * @return void
     */
    protected function updateEntities(array $entitiesToUpdate): void
    {
        foreach ($entitiesToUpdate as $entityClassName => $entities) {
            $connection = $this->getWriteConnection($entityClassName);

            $entities = $this->preSave($entities, $connection);
            $entities = $this->preUpdate($entities, $connection);
            $statement = $this->buildUpdateStatement($entityClassName, $entities);
            $this->executeStatement($statement, $entityClassName, 'update');
            $this->postUpdate($entities, $connection);
            $this->postSave($entities, $connection);
        }
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface[] $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]
     */
    protected function preSave(array $entities, ConnectionInterface $connection): array
    {
        array_filter($entities, function (ActiveRecordInterface $entity) use ($connection) {
            return $entity->preSave($connection);
        });

        return $entities;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface[] $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return void
     */
    protected function postSave(array $entities, ConnectionInterface $connection): void
    {
        foreach ($entities as $entity) {
            $entity->postSave($connection);
        }
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface[] $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]
     */
    protected function preInsert(array $entities, ConnectionInterface $connection): array
    {
        array_filter($entities, function (ActiveRecordInterface $entity) use ($connection) {
            return $entity->preInsert($connection);
        });

        return $entities;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface[] $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return void
     */
    protected function postInsert(array $entities, ConnectionInterface $connection): void
    {
        foreach ($entities as $entity) {
            $entity->postInsert($connection);
        }
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface[] $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]
     */
    protected function preUpdate(array $entities, ConnectionInterface $connection): array
    {
        array_filter($entities, function (ActiveRecordInterface $entity) use ($connection) {
            return $entity->preUpdate($connection);
        });

        return $entities;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface[] $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return void
     */
    protected function postUpdate(array $entities, ConnectionInterface $connection): void
    {
        foreach ($entities as $entity) {
            $entity->postUpdate($connection);
        }
    }

    /**
     * @param \PDOStatement $statement
     * @param string $entityClassName
     * @param string $type
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function executeStatement(PDOStatement $statement, string $entityClassName, string $type): void
    {
        try {
            $connection = $this->getWriteConnection($entityClassName);

            $connection->beginTransaction();
            $statement->execute();
            $connection->commit();

            $this->clear();
        } catch (Throwable $throwable) {
            $connection->rollBack();

            throw new Exception(sprintf('Failed to execute %s statement for %s. Error: %s', $type, $entityClassName, $throwable->getMessage()), 0, $throwable);
        }
    }

    /**
     * @param string $entityClassName
     *
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    protected function getWriteConnection(string $entityClassName): ConnectionInterface
    {
        if (!isset($this->writeConnections[$entityClassName])) {
            $tableMapClass = $this->getTableMapClass($entityClassName);
            $this->writeConnections[$entityClassName] = Propel::getServiceContainer()->getWriteConnection($tableMapClass::DATABASE_NAME);
        }

        return $this->writeConnections[$entityClassName];
    }

    /**
     * @return void
     */
    protected function clear(): void
    {
        $this->entitiesToInsert = [];
        $this->entitiesToUpdate = [];
    }

    /**
     * @param string $entityClassName
     * @param array $entities
     *
     * @return \PDOStatement
     */
    protected function buildInsertStatement(string $entityClassName, array $entities): PDOStatement
    {
        $tableMapClass = $this->getTableMapClass($entityClassName);
        $columnMapCollection = $tableMapClass->getColumns();
        $adapter = $this->getAdapter();
        $requiresPrimaryKeyValue = ($adapter instanceof PgsqlAdapter);

        $tableMapClassName = $entityClassName::TABLE_MAP;

        $sql = '';
        $keyIndex = 0;
        $values = [];

        foreach ($entities as $entity) {
            $entity = $this->updateDateTimes($entity);
            $valuesForInsert = $this->prepareValuesForInsert(
                $columnMapCollection,
                $tableMapClass,
                $tableMapClassName,
                $entity,
                $requiresPrimaryKeyValue
            );

            $columnNamesForInsertWithPdoPlaceholder = array_map(function (array $columnDetails) use (&$keyIndex, $tableMapClass) {
                if ($columnDetails['columnMap']->isPrimaryKey() && $tableMapClass->getPrimaryKeyMethodInfo() !== null) {
                    return sprintf('(SELECT nextval(\'%s\'))', $tableMapClass->getPrimaryKeyMethodInfo());
                }

                return sprintf(':p%d', $keyIndex++);
            }, $valuesForInsert);

            $values = array_merge($values, array_values($valuesForInsert));

            $sql .= sprintf(
                'INSERT INTO %s (%s) VALUES (%s);',
                $tableMapClass->getName(),
                implode(', ', array_keys($columnNamesForInsertWithPdoPlaceholder)),
                implode(', ', $columnNamesForInsertWithPdoPlaceholder)
            );
        }

        $statement = $this->prepareStatement($sql, $this->getWriteConnection($entityClassName));
        $statement = $this->bindInsertValues($statement, $values);

        return $statement;
    }

    /**
     * @param \Propel\Runtime\Map\ColumnMap[] $columnMapCollection
     * @param \Propel\Runtime\Map\TableMap $tableMapClass
     * @param string $tableMapClassName
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param bool $requiresPrimaryKeyValue
     *
     * @return array
     */
    protected function prepareValuesForInsert(
        array $columnMapCollection,
        TableMap $tableMapClass,
        string $tableMapClassName,
        ActiveRecordInterface $entity,
        bool $requiresPrimaryKeyValue
    ): array {
        $valuesForInsert = [];

        $entityData = $entity->toArray(TableMap::TYPE_FIELDNAME);

        foreach ($columnMapCollection as $columnIdentifier => $columnMap) {
            $quotedColumnName = $this->quote($columnMap->getName(), $tableMapClass);
            if ($columnMap->isPrimaryKey()) {
                if (!$requiresPrimaryKeyValue || $tableMapClass->getPrimaryKeyMethodInfo() === null) {
                    continue;
                }

                $value = sprintf('(SELECT nextval(\'%s\'))', $tableMapClass->getPrimaryKeyMethodInfo());
                $valuesForInsert[$quotedColumnName] = $this->prepareValuesForSave($columnMap, $entityData, $value);

                continue;
            }

            $columnIdentifier = sprintf('COL_%s', $columnIdentifier);
            $fullyQualifiedColumnName = constant(sprintf('%s::%s', $tableMapClassName, $columnIdentifier));

            if ($entity->isColumnModified($fullyQualifiedColumnName)) {
                $valuesForInsert[$quotedColumnName] = $this->prepareValuesForSave($columnMap, $entityData);
            }
        }

        return $valuesForInsert;
    }

    /**
     * @param string $sql
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @throws \Spryker\Zed\Propel\Exception\StatementNotPreparedException
     *
     * @return \PDOStatement
     */
    protected function prepareStatement(string $sql, ConnectionInterface $connection): PDOStatement
    {
        $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $statement = $connection->prepare($sql);

        if (!$statement) {
            throw new StatementNotPreparedException(sprintf('Wasn\'t able to create a statement with provided query: `%s`', $sql));
        }

        return $statement;
    }

    /**
     * @param \PDOStatement $statement
     * @param array $values
     *
     * @return \PDOStatement
     */
    protected function bindInsertValues(PDOStatement $statement, array $values): PDOStatement
    {
        $values = array_filter($values, function (array $columnDetails) {
            return !$columnDetails['columnMap']->isPrimaryKey();
        });

        foreach (array_values($values) as $index => $value) {
            $statement->bindValue(sprintf(':p%d', $index), $value['value'], $value['type']);
        }

        return $statement;
    }

    /**
     * @param \Propel\Runtime\Map\ColumnMap $columnMap
     * @param array $entityData
     * @param string|null $defaultValue
     *
     * @return array
     */
    protected function prepareValuesForSave(ColumnMap $columnMap, array $entityData, ?string $defaultValue = null): array
    {
        $value = $defaultValue ?: $entityData[$columnMap->getName()];
        if (is_array($value)) {
            $value = json_encode($value);
        }

        return [
            'columnMap' => $columnMap,
            'value' => $value,
            'type' => $columnMap->getPdoType(),
        ];
    }

    /**
     * @param string $entityClassName
     * @param array $entities
     *
     * @return \PDOStatement
     */
    protected function buildUpdateStatement(string $entityClassName, array $entities): PDOStatement
    {
        $tableMapClass = $this->getTableMapClass($entityClassName);
        $columnMapCollection = $tableMapClass->getColumns();
        $tableMapClassName = $entityClassName::TABLE_MAP;

        $sql = '';
        $keyIndex = 0;
        $values = [];

        foreach ($entities as $entity) {
            $entity = $this->updateDateTimes($entity);

            [$valuesForUpdate, $idColumnValuesAndTypes] = $this->prepareValuesForUpdate(
                $columnMapCollection,
                $tableMapClass,
                $tableMapClassName,
                $entity
            );

            $columnNamesForUpdateWithPdoPlaceholder = array_map(function ($columnName) use (&$keyIndex, $tableMapClass) {
                return sprintf('%s=:p%d', $this->quote($columnName, $tableMapClass), $keyIndex++);
            }, array_keys($valuesForUpdate));

            $values = array_merge($values, array_values($valuesForUpdate), array_values($idColumnValuesAndTypes));

            $whereClauses = [];

            foreach (array_keys($idColumnValuesAndTypes) as $primaryKeyColumnName) {
                $whereClauses[] = sprintf('%s.%s=:p%d', $tableMapClass->getName(), $primaryKeyColumnName, $keyIndex++);
            }

            $sql .= sprintf(
                'UPDATE %s SET %s WHERE %s;',
                $tableMapClass->getName(),
                implode(', ', $columnNamesForUpdateWithPdoPlaceholder),
                implode(' AND ', $whereClauses)
            );
        }

        $statement = $this->prepareStatement($sql, $this->getWriteConnection($entityClassName));
        $statement = $this->bindUpdateValues($statement, $values);

        return $statement;
    }

    /**
     * @param array $columnMapCollection
     * @param \Propel\Runtime\Map\TableMap $tableMapClass
     * @param string $tableMapClassName
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return array
     */
    protected function prepareValuesForUpdate(
        array $columnMapCollection,
        TableMap $tableMapClass,
        string $tableMapClassName,
        ActiveRecordInterface $entity
    ): array {
        $valuesForUpdate = [];
        $idColumnValuesAndTypes = [];
        $entityData = $entity->toArray(TableMap::TYPE_FIELDNAME);

        foreach ($columnMapCollection as $columnIdentifier => $columnMap) {
            if ($columnMap->isPrimaryKey()) {
                $idColumnValuesAndTypes[$columnMap->getName()] = $this->prepareValuesForSave($columnMap, $entityData);

                continue;
            }

            $columnIdentifier = sprintf('COL_%s', $columnIdentifier);
            $fullyQualifiedColumnName = constant(sprintf('%s::%s', $tableMapClassName, $columnIdentifier));

            if ($entity->isColumnModified($fullyQualifiedColumnName)) {
                $valuesForUpdate[$columnMap->getName()] = $this->prepareValuesForSave($columnMap, $entityData);
            }
        }

        return [$valuesForUpdate, $idColumnValuesAndTypes];
    }

    /**
     * @param \PDOStatement $statement
     * @param array $values
     *
     * @return \PDOStatement
     */
    protected function bindUpdateValues(PDOStatement $statement, array $values): PDOStatement
    {
        foreach (array_values($values) as $index => $value) {
            $statement->bindValue(sprintf(':p%d', $index), $value['value'], $value['type']);
        }

        return $statement;
    }

    /**
     * @param string $columnName
     * @param \Propel\Runtime\Map\TableMap $tableMapClass
     *
     * @return string
     */
    protected function quote(string $columnName, TableMap $tableMapClass): string
    {
        if ($tableMapClass->isIdentifierQuotingEnabled()) {
            return $this->getAdapter()->quote($columnName);
        }

        return $columnName;
    }

    /**
     * @return \Propel\Runtime\Adapter\AdapterInterface
     */
    protected function getAdapter(): AdapterInterface
    {
        if ($this->adapter === null) {
            $this->adapter = Propel::getServiceContainer()->getAdapter();
        }

        return $this->adapter;
    }

    /**
     * @param string $entityClassName
     *
     * @return \Propel\Runtime\Map\TableMap
     */
    protected function getTableMapClass(string $entityClassName): TableMap
    {
        if (!isset($this->tableMapClasses[$entityClassName])) {
            $tableMapClassName = $entityClassName::TABLE_MAP;
            $this->tableMapClasses[$entityClassName] = new $tableMapClassName();
        }

        return $this->tableMapClasses[$entityClassName];
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    protected function updateDateTimes(ActiveRecordInterface $entity): ActiveRecordInterface
    {
        $highPrecisionDateTime = $this->getHighPrecisionDateTime();

        if ($entity->isNew()) {
            if (method_exists($entity, 'setCreatedAt')) {
                $entity->setCreatedAt($highPrecisionDateTime);
            }
        }

        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt($highPrecisionDateTime);
        }

        return $entity;
    }

    /**
     * @return \DateTime
     */
    protected function getHighPrecisionDateTime(): DateTime
    {
        if ($this->highPrecisionDateTime === null) {
            $this->highPrecisionDateTime = PropelDateTime::createHighPrecision();
        }

        return $this->highPrecisionDateTime;
    }

    /**
     * @param \Propel\Runtime\Map\ColumnMap $columnMap
     * @param \Propel\Runtime\Map\TableMap $tableMap
     * @param string|int|float|bool|array $value
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return string|int|float|bool
     */
    protected function getValue(ColumnMap $columnMap, TableMap $tableMap, $value)
    {
        if ($columnMap->getType() === 'ENUM' && $value !== null) {
            /** @psalm-suppress UndefinedMethod */
            $valueSet = $tableMap::getValueSet($columnMap->getFullyQualifiedName());
            if (!in_array($value, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', (string)$value));
            }
            $value = array_search($value, $valueSet);
        }

        if ($columnMap->getType() === 'LONGVARCHAR' && is_array($value)) {
            $value = (string)json_encode($value);
        }

        return $value;
    }
}
