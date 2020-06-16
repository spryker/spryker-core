<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Persistence\BatchProcessor;

use Exception;
use PDO;
use PDOStatement;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Adapter\AdapterInterface;
use Propel\Runtime\Adapter\Pdo\PgsqlAdapter;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\ColumnMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\Util\PropelDateTime;
use Throwable;

trait ActiveRecordBatchProcessorTrait
{
    /**
     * @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface[][]
     */
    protected static $entitiesToInsert = [];

    /**
     * @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface[][]
     */
    protected static $entitiesToUpdate = [];

    /**
     * @var \Propel\Runtime\Map\TableMap[]
     */
    protected $tableMapClasses = [];

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @var \Propel\Runtime\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return void
     */
    public function persist(ActiveRecordInterface $entity): void
    {
        $className = get_class($entity);

        if (!$entity->isModified()) {
            return;
        }

        if ($entity->isNew()) {
            if (!isset(static::$entitiesToInsert[$className])) {
                static::$entitiesToInsert[$className] = [];
            }

            static::$entitiesToInsert[$className][] = $entity;

            return;
        }

        if (!isset(static::$entitiesToUpdate[$className])) {
            static::$entitiesToUpdate[$className] = [];
        }

        static::$entitiesToUpdate[$className][] = $entity;
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        $this->commitEntities(static::$entitiesToInsert, 'insert');
        $this->commitEntities(static::$entitiesToUpdate, 'update');

        return true;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface[] $entitiesToSave
     * @param string $type
     *
     * @return void
     */
    protected function commitEntities(array $entitiesToSave, string $type = 'insert'): void
    {
        $preMethodName = sprintf('pre%s', ucfirst($type));
        $postMethodName = sprintf('post%s', ucfirst($type));
        $buildMethodName = sprintf('build%sStatement', ucfirst($type));

        foreach ($entitiesToSave as $entityClassName => $entities) {
            $entities = $this->preSave($entities);
            $entities = $this->{$preMethodName}($entities);
            $statement = $this->{$buildMethodName}($entityClassName, $entities);
            $this->executeStatement($statement, $entityClassName, $type);
            $this->{$postMethodName}($entities);
            $this->postSave($entities);
        }
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface[] $entities
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]
     */
    protected function preSave(array $entities): array
    {
        array_filter($entities, function (ActiveRecordInterface $entity) {
            return $entity->preSave();
        });

        return $entities;
    }

    /**
     * @param array $entities
     *
     * @return void
     */
    protected function postSave(array $entities): void
    {
        foreach ($entities as $entity) {
            $entity->postSave();
        }
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface[] $entities
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]
     */
    protected function preInsert(array $entities): array
    {
        array_filter($entities, function (ActiveRecordInterface $entity) {
            return $entity->preInsert();
        });

        return $entities;
    }

    /**
     * @param array $entities
     *
     * @return void
     */
    protected function postInsert(array $entities): void
    {
        foreach ($entities as $entity) {
            $entity->postInsert();
        }
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface[] $entities
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]
     */
    protected function preUpdate(array $entities): array
    {
        array_filter($entities, function (ActiveRecordInterface $entity) {
            return $entity->preUpdate();
        });

        return $entities;
    }

    /**
     * @param array $entities
     *
     * @return void
     */
    protected function postUpdate(array $entities): void
    {
        foreach ($entities as $entity) {
            $entity->postUpdate();
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
            $this->getConnection()->beginTransaction();
            $statement->execute();
            $this->getConnection()->commit();

            $this->clear();
        } catch (Throwable $throwable) {
            $this->getConnection()->rollBack();

            throw new Exception(sprintf('Failed to execute %s statement for %s. Error: %s', $type, $entityClassName, $throwable->getMessage()), 0, $throwable);
        }
    }

    /**
     * @return void
     */
    protected function clear(): void
    {
        static::$entitiesToInsert = [];
        static::$entitiesToUpdate = [];
    }

    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    protected function getConnection()
    {
        if ($this->connection === null) {
            $this->connection = Propel::getConnection();
        }

        return $this->connection;
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
            $valuesForInsert = [];

            $entityData = $entity->toArray(TableMap::TYPE_FIELDNAME);

            foreach ($columnMapCollection as $columnIdentifier => $columnMap) {
                if ($columnMap->isPrimaryKey() && !$requiresPrimaryKeyValue) {
                    continue;
                }

                if ($columnMap->isPrimaryKey() && $tableMapClass->getPrimaryKeyMethodInfo() !== null) {
                    $value = sprintf('(SELECT nextval(\'%s\'))', $tableMapClass->getPrimaryKeyMethodInfo());
                    $valuesForInsert[$columnMap->getName()] = $this->prepareValuesForSave($columnMap, $entityData, $value);

                    continue;
                }

                $columnIdentifier = sprintf('COL_%s', $columnIdentifier);
                $fullyQualifiedColumnName = constant(sprintf('%s::%s', $tableMapClassName, $columnIdentifier));

                if ($entity->isColumnModified($fullyQualifiedColumnName)) {
                    $valuesForInsert[$columnMap->getName()] = $this->prepareValuesForSave($columnMap, $entityData);
                }
            }

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

        return $this->prepareStatement($sql, $values);
    }

    /**
     * @param string $sql
     * @param array $values
     * @param bool $isInsert
     *
     * @return \PDOStatement
     */
    protected function prepareStatement(string $sql, array $values, bool $isInsert = true): PDOStatement
    {
        $connection = $this->getConnection();
        $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $statement = $connection->prepare($sql);

        if ($isInsert) {
            $values = array_filter($values, function (array $columnDetails) {
                return !$columnDetails['columnMap']->isPrimaryKey();
            });
        }

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

        return $this->prepareStatement($sql, $values, false);
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
        $highPrecisionDateTime = PropelDateTime::createHighPrecision();

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
            $valueSet = $tableMap::getValueSet($columnMap->getFullyQualifiedName());
            if (!in_array($value, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
            }
            $value = array_search($value, $valueSet);
        }

        if ($columnMap->getType() === 'LONGVARCHAR' && is_array($value)) {
            $value = (string)json_encode($value);
        }

        return $value;
    }
}
