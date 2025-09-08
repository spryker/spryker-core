<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Persistence\BatchProcessor;

use DateTime;
use Exception;
use PDO;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Adapter\AdapterInterface;
use Propel\Runtime\Adapter\Pdo\PgsqlAdapter;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Connection\StatementInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\ColumnMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\Util\PropelDateTime;
use Spryker\Zed\Propel\Exception\StatementNotPreparedException;
use Spryker\Zed\Propel\Persistence\BatchEntityPostSaveInterface;
use Spryker\Zed\Propel\PropelConfig;
use Throwable;

/**
 * This trait is not capable to do insert/update of related entities.
 * P&S is not triggered while using this trait.
 */
trait ActiveRecordBatchProcessorTrait
{
    /**
     * @var int
     */
    protected const UPDATE_CHUNK_SIZE = 200;

    /**
     * @var array<string, array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>>
     */
    protected array $entitiesToInsert = [];

    /**
     * @var array<string, array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>>
     */
    protected array $entitiesToUpdate = [];

    /**
     * @var array<string, array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>>
     */
    protected array $entitiesToRemove = [];

    /**
     * @var array<\Propel\Runtime\Map\TableMap>
     */
    protected $tableMapClasses = [];

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
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return void
     */
    public function remove(ActiveRecordInterface $entity): void
    {
        if ($entity->isNew()) {
            return;
        }

        $className = get_class($entity);

        if (!isset($this->entitiesToRemove[$className])) {
            $this->entitiesToRemove[$className] = [];
        }

        $this->entitiesToRemove[$className][] = $entity;
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        $this->removeEntities($this->entitiesToRemove);
        $this->insertEntities($this->entitiesToInsert);
        $this->updateEntities($this->entitiesToUpdate);

        $this->resetEntitiesForCommit();

        return true;
    }

    /**
     * @return bool
     */
    public function commitIdentical(): bool
    {
        $this->insertIdenticalEntities($this->entitiesToInsert);
        $this->updateEntities($this->entitiesToUpdate);

        $this->resetEntitiesForCommit();

        return true;
    }

    /**
     * @param array<string, array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>> $entitiesToInsert
     *
     * @return void
     */
    protected function insertEntities(array $entitiesToInsert): void
    {
        foreach ($entitiesToInsert as $entityClassName => $entities) {
            $connection = $this->getWriteConnection($entityClassName);

            $entities = $this->executeEntitiesPreSave($entities, $connection);
            $entities = $this->executePreInsert($entities, $connection);
            $statements = $this->buildInsertStatements($entityClassName, $entities);
            $this->executeStatements($statements, $entityClassName, 'insert');
            $this->postSaveEntityProcession($entities, $connection);
            $this->executePostInsert($entities, $connection);
            $this->executePostSave($entities, $connection);
        }
    }

    /**
     * @param array<string, array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>> $entitiesToRemove
     *
     * @return void
     */
    protected function removeEntities(array $entitiesToRemove): void
    {
        foreach ($entitiesToRemove as $entityClassName => $entities) {
            $connection = $this->getWriteConnection($entityClassName);

            $entities = $this->executeEntitiesPreDelete($entities, $connection);
            $entities = $this->executePreDelete($entities, $connection);
            $this->deleteEntitiesInBulk($entityClassName, $entities);
            $this->postDeleteEntityProcession($entities, $connection);
            $this->executePostDelete($entities, $connection);
        }
    }

    /**
     * All entities have to be identical in terms of modified columns.
     *
     * @param array<string, array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>> $entitiesToInsert
     *
     * @return void
     */
    protected function insertIdenticalEntities(array $entitiesToInsert): void
    {
        foreach ($entitiesToInsert as $entityClassName => $entities) {
            $connection = $this->getWriteConnection($entityClassName);
            $entities = $this->executeEntitiesPreSave($entities, $connection);
            $entities = $this->executePreInsert($entities, $connection);
            $statement = $this->buildInsertStatementIdentical($entityClassName, $entities);
            $this->executeStatements([$statement], $entityClassName, 'insert');
            $this->postSaveEntityProcession($entities, $connection);
            $this->executePostInsert($entities, $connection);
        }
    }

    /**
     * @param array<string, array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>> $entitiesToUpdate
     *
     * @return void
     */
    protected function updateEntities(array $entitiesToUpdate): void
    {
        foreach ($entitiesToUpdate as $entityClassName => $entities) {
            $connection = $this->getWriteConnection($entityClassName);

            $entities = $this->executeEntitiesPreSave($entities, $connection);
            $entities = $this->executePreUpdate($entities, $connection);
            $statements = $this->buildUpdateStatements($entityClassName, $entities);
            $this->executeStatements($statements, $entityClassName, 'update');
            $this->postSaveEntityProcession($entities, $connection);
            $this->executePostUpdate($entities, $connection);
            $this->executePostSave($entities, $connection);
        }
    }

    /**
     * @param array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    protected function executeEntitiesPreSave(array $entities, ConnectionInterface $connection): array
    {
        foreach ($entities as $entity) {
            $entity->preSave($connection);
            if (method_exists($entity, 'preSaveSynchronizationBehavior')) {
                $entity->preSaveSynchronizationBehavior();
            }
        }

        return $entities;
    }

    /**
     * @param array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    protected function executeEntitiesPreDelete(array $entities, ConnectionInterface $connection): array
    {
        foreach ($entities as $entity) {
            $entity->preDelete($connection);
            if (method_exists($entity, 'postDeleteSynchronizationBehavior')) {
                $entity->postDeleteSynchronizationBehavior();
            }
        }

        return $entities;
    }

    /**
     * @param array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return void
     */
    protected function executePostSave(array $entities, ConnectionInterface $connection): void
    {
        $entity = $entities[0];
        if ($entity instanceof BatchEntityPostSaveInterface) {
            foreach ($entities as $entity) {
                $entity->batchPostSave();
            }
            $entity->recursiveCommit();

            return;
        }

        foreach ($entities as $entity) {
            $entity->postSave($connection);
            if (method_exists($entity, 'postSaveSynchronizationBehavior')) {
                $entity->postSaveSynchronizationBehavior();
            }
        }
    }

    /**
     * @param array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    protected function executePreInsert(array $entities, ConnectionInterface $connection): array
    {
        array_filter($entities, function (ActiveRecordInterface $entity) use ($connection) {
            return $entity->preInsert($connection);
        });

        return $entities;
    }

    /**
     * @param array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    protected function executePreDelete(array $entities, ConnectionInterface $connection): array
    {
        array_filter($entities, function (ActiveRecordInterface $entity) use ($connection) {
            return $entity->preDelete($connection);
        });

        return $entities;
    }

    /**
     * @param array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return void
     */
    protected function executePostInsert(array $entities, ConnectionInterface $connection): void
    {
        foreach ($entities as $entity) {
            $entity->postInsert($connection);
        }
    }

    /**
     * @param array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return void
     */
    protected function executePostDelete(array $entities, ConnectionInterface $connection): void
    {
        foreach ($entities as $entity) {
            $entity->postDelete($connection);
        }
    }

    /**
     * @param array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    protected function executePreUpdate(array $entities, ConnectionInterface $connection): array
    {
        array_filter($entities, function (ActiveRecordInterface $entity) use ($connection) {
            return $entity->preUpdate($connection);
        });

        return $entities;
    }

    /**
     * @param array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return void
     */
    protected function executePostUpdate(array $entities, ConnectionInterface $connection): void
    {
        foreach ($entities as $entity) {
            $entity->postUpdate($connection);
        }
    }

    /**
     * @param array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return void
     */
    protected function postSaveEntityProcession(array $entities, ConnectionInterface $connection): void
    {
        foreach ($entities as $entity) {
            $entity->resetModified();
            $entity->setNew(false);
        }
    }

    /**
     * @param array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $entities
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return void
     */
    protected function postDeleteEntityProcession(array $entities, ConnectionInterface $connection): void
    {
        foreach ($entities as $entity) {
            $entity->setDeleted(true);
        }
    }

    /**
     * @param array<\Propel\Runtime\Connection\StatementInterface> $statements
     * @param string $entityClassName
     * @param string $type
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function executeStatements(array $statements, string $entityClassName, string $type): void
    {
        try {
            $connection = $this->getWriteConnection($entityClassName);

            $connection->beginTransaction();
            foreach ($statements as $statement) {
                $statement->execute();
                $statement->closeCursor();
            }
            $connection->commit();
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
        $tableMapClass = $this->getTableMapClass($entityClassName);

        return Propel::getServiceContainer()->getWriteConnection($tableMapClass::DATABASE_NAME);
    }

    /**
     * @return bool
     */
    protected function isMysql(): bool
    {
        return Propel::getServiceContainer()->getAdapterClass() === PropelConfig::DB_ENGINE_MYSQL;
    }

    /**
     * @return void
     */
    protected function resetEntitiesForCommit(): void
    {
        $this->entitiesToInsert = [];
        $this->entitiesToUpdate = [];
        $this->entitiesToRemove = [];
    }

    /**
     * @param string $entityClassName
     * @param array $entitiesToRemove
     *
     * @return void
     */
    protected function deleteEntitiesInBulk(string $entityClassName, array $entitiesToRemove): void
    {
        $primaryKeys = [];
        foreach ($entitiesToRemove as $entity) {
            if (!$entity instanceof ActiveRecordInterface) {
                continue;
            }

            $primaryKey = $entity->getPrimaryKey();

            if (is_array($primaryKey)) {
                continue;
            }

            $primaryKeys[] = $primaryKey;
        }

        if (!$primaryKeys) {
            return;
        }

        $queryClass = $entityClassName . 'Query';
        /** @var \Propel\Runtime\ActiveQuery\ModelCriteria $query */
        $query = new $queryClass();
        $query->filterByPrimaryKeys($primaryKeys)->delete();
    }

    /**
     * @param string $entityClassName
     * @param array $entities
     *
     * @return array<\Propel\Runtime\Connection\StatementInterface>
     */
    protected function buildInsertStatements(string $entityClassName, array $entities): array
    {
        $tableMapClass = $this->getTableMapClass($entityClassName);

        $statements = [];

        $statementsGroupedByInsertedColumns = [];

        foreach ($entities as $entity) {
            $keyIndex = 0;
            $entity = $this->updateDateTimes($entity);
            $valuesForInsert = $this->prepareValuesForInsert(
                $tableMapClass->getColumns(),
                $tableMapClass,
                $entityClassName::TABLE_MAP,
                $entity,
                $this->requiresPrimaryKeyValue(),
            );

            $columnNamesForInsertWithPdoPlaceholder = array_map(function (array $columnDetails) use (&$keyIndex, $tableMapClass) {
                if ($columnDetails['columnMap']->isPrimaryKey() && $tableMapClass->getPrimaryKeyMethodInfo() !== null) {
                    return sprintf('(SELECT nextval(\'%s\'))', $tableMapClass->getPrimaryKeyMethodInfo());
                }

                return sprintf(':p%d', $keyIndex++);
            }, $valuesForInsert);

            $key = implode(',', array_keys($columnNamesForInsertWithPdoPlaceholder));

            $statementsGroupedByInsertedColumns[$key][] = $entity;
        }

        foreach ($statementsGroupedByInsertedColumns as $entities) {
            $statements[] = $this->buildInsertStatementIdentical($entityClassName, $entities);
        }

        return $statements;
    }

    /**
     * @param string $entityClassName
     * @param array $entities
     *
     * @return \Propel\Runtime\Connection\StatementInterface
     */
    protected function buildInsertStatementIdentical(string $entityClassName, array $entities): StatementInterface
    {
        $tableMapClass = $this->getTableMapClass($entityClassName);
        $requiresPrimaryKeyValue = $this->requiresPrimaryKeyValue();

        $connection = $this->getWriteConnection($entityClassName);
        $keyIndex = 0;
        $valuesForBind = [];
        $entitiesQueryParams = [];
        $entityQueryParams = [];

        foreach ($entities as $entity) {
            $entity = $this->updateDateTimes($entity);
            $valuesForInsert = $this->prepareValuesForInsert(
                $tableMapClass->getColumns(),
                $tableMapClass,
                $entityClassName::TABLE_MAP,
                $entity,
                $requiresPrimaryKeyValue,
            );

            foreach ($valuesForInsert as $columnDetails) {
                if ($requiresPrimaryKeyValue && $columnDetails['columnMap']->isPrimaryKey() && $tableMapClass->getPrimaryKeyMethodInfo() !== null) {
                    $entityQueryParams[] = sprintf('(SELECT nextval(\'%s\'))', $tableMapClass->getPrimaryKeyMethodInfo());

                    continue;
                }

                $queryParamKey = sprintf(':p%d', $keyIndex++);
                $valuesForBind[$queryParamKey] = $columnDetails;
                $entityQueryParams[] = $queryParamKey;
            }

            $entitiesQueryParams[] = sprintf('(%s)', implode(', ', $entityQueryParams));
            $entityQueryParams = [];
        }

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES %s;',
            $tableMapClass->getName(),
            implode(', ', array_keys($valuesForInsert)),
            implode(', ', $entitiesQueryParams),
        );

        $statement = $this->prepareStatement($sql, $connection);
        $statement = $this->bindInsertValuesIdentical($statement, $valuesForBind);

        return $statement;
    }

    /**
     * @deprecated Use {@link buildInsertStatementIdentical()} instead.
     *
     * @param string $entityClassName
     * @param array $entities
     *
     * @return \Propel\Runtime\Connection\StatementInterface
     */
    protected function buildInsertStatementForIdenticalEntities(string $entityClassName, array $entities): StatementInterface
    {
        $tableMapClass = $this->getTableMapClass($entityClassName);

        $connection = $this->getWriteConnection($entityClassName);
        $statements = [];

        foreach ($entities as $entity) {
            $keyIndex = 0;
            $entity = $this->updateDateTimes($entity);
            $valuesForInsert = $this->prepareValuesForInsert(
                $tableMapClass->getColumns(),
                $tableMapClass,
                $entityClassName::TABLE_MAP,
                $entity,
                $this->requiresPrimaryKeyValue(),
            );

            $columnNamesForInsertWithPdoPlaceholder = array_map(function (array $columnDetails) use (&$keyIndex, $tableMapClass) {
                if ($columnDetails['columnMap']->isPrimaryKey() && $tableMapClass->getPrimaryKeyMethodInfo() !== null) {
                    return sprintf('(SELECT nextval(\'%s\'))', $tableMapClass->getPrimaryKeyMethodInfo());
                }

                return sprintf(':p%d', $keyIndex++);
            }, $valuesForInsert);

            $sql = sprintf(
                'INSERT INTO %s (%s) VALUES (%s);',
                $tableMapClass->getName(),
                implode(', ', array_keys($columnNamesForInsertWithPdoPlaceholder)),
                implode(', ', $columnNamesForInsertWithPdoPlaceholder),
            );

            $statement = $this->prepareStatement($sql, $connection);
            $statement = $this->bindInsertValues($statement, $valuesForInsert);

            $statements[] = $statement;
        }

        return $statements;
    }

    /**
     * @param array<\Propel\Runtime\Map\ColumnMap> $columnMapCollection
     * @param \Propel\Runtime\Map\TableMap $tableMapClass
     * @param string $tableMapClassName
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param bool $requiresPrimaryKeyValue
     *
     * @return array<string, mixed>
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
            if ($columnMap->isPrimaryKey() && !$columnMap->isForeignKey()) {
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
     * @return \Propel\Runtime\Connection\StatementInterface
     */
    protected function prepareStatement(string $sql, ConnectionInterface $connection): StatementInterface
    {
        $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $statement = $connection->prepare($sql);

        if (!$statement) {
            throw new StatementNotPreparedException(sprintf('Wasn\'t able to create a statement with provided query: `%s`', $sql));
        }

        return $statement;
    }

    /**
     * @param \Propel\Runtime\Connection\StatementInterface $statement
     * @param array $values
     *
     * @return \Propel\Runtime\Connection\StatementInterface
     */
    protected function bindInsertValues(StatementInterface $statement, array $values): StatementInterface
    {
        $values = array_filter($values, function (array $columnDetails) {
            /** @var \Propel\Runtime\Map\ColumnMap $columnMap */
            $columnMap = $columnDetails['columnMap'];

            return !($columnMap->isPrimaryKey() && !$columnMap->isForeignKey());
        });

        foreach (array_values($values) as $index => $value) {
            $statement->bindValue(sprintf(':p%d', $index), $value['value'], $value['type']);
        }

        return $statement;
    }

    /**
     * @param \Propel\Runtime\Connection\StatementInterface $statement
     * @param array $valuesForBind
     *
     * @return \Propel\Runtime\Connection\StatementInterface
     */
    protected function bindInsertValuesIdentical(StatementInterface $statement, array $valuesForBind): StatementInterface
    {
        foreach ($valuesForBind as $queryParam => $value) {
            $statement->bindValue($queryParam, $value['value'], $value['type']);
        }

        return $statement;
    }

    /**
     * @param \Propel\Runtime\Map\ColumnMap $columnMap
     * @param array $entityData
     * @param string|null $defaultValue
     *
     * @return array<string, mixed>
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
     * @return array<\Propel\Runtime\Connection\StatementInterface>
     */
    protected function buildUpdateStatements(string $entityClassName, array $entities): array
    {
        if (!$this->isMysql()) {
            return $this->buildPostgresUpdateStatements($entityClassName, $entities);
        }

        $tableMapClass = $this->getTableMapClass($entityClassName);

        $connection = $this->getWriteConnection($entityClassName);
        $statements = [];

        $chunkEntities = array_chunk($entities, static::UPDATE_CHUNK_SIZE);

        foreach ($chunkEntities as $chunkEntity) {
            $keyIndex = 0;
            $sql = '';
            $values = [];
            foreach ($chunkEntity as $entity) {
                $whereClauses = [];
                $columnNamesForUpdateWithPdoPlaceholder = [];
                $entityData = [];
                $entity = $this->updateDateTimes($entity);

                [$valuesForUpdate, $idColumnValuesAndTypes] = $this->prepareValuesForUpdate(
                    $tableMapClass->getColumns(),
                    $tableMapClass,
                    $entityClassName::TABLE_MAP,
                    $entity,
                );

                foreach ($valuesForUpdate as $columnName => $value) {
                    $index = $keyIndex++;
                    $entityData[$index] = $value;
                    $columnNamesForUpdateWithPdoPlaceholder[] = sprintf('%s=:p%d', $this->quote($columnName, $tableMapClass), $index);
                }

                foreach ($idColumnValuesAndTypes as $primaryKeyColumnName => $valueForUpdate) {
                    $index = $keyIndex++;
                    $entityData[$index] = $valueForUpdate;
                    $whereClauses[] = sprintf('%s.%s=:p%d', $tableMapClass->getName(), $primaryKeyColumnName, $index);
                }

                $sql .= sprintf(
                    'UPDATE %s SET %s WHERE %s;',
                    $tableMapClass->getName(),
                    implode(', ', $columnNamesForUpdateWithPdoPlaceholder),
                    implode(' AND ', $whereClauses),
                );
                $values[] = $entityData;
            }

            $statement = $this->prepareStatement($sql, $connection);
            $statement = $this->bindUpdateValues($statement, $values);
            $statements[] = $statement;
        }

        return $statements;
    }

    /**
     * @param string $entityClassName
     * @param array $entities
     *
     * @return array<\Propel\Runtime\Connection\StatementInterface>
     */
    protected function buildPostgresUpdateStatements(string $entityClassName, array $entities): array
    {
        //  MariaDB and PostgreSQL have significantly different syntax for batch updates.
        //  It's more maintainable to keep them separate rather than forcing a generic method.
        //  Note: only the MariaDB version is currently optimized for batch update performance.
        $tableMapClass = $this->getTableMapClass($entityClassName);

        $connection = $this->getWriteConnection($entityClassName);
        $statements = [];

        foreach ($entities as $entity) {
            $keyIndex = 0;
            $entity = $this->updateDateTimes($entity);

            [$valuesForUpdate, $idColumnValuesAndTypes] = $this->prepareValuesForUpdate(
                $tableMapClass->getColumns(),
                $tableMapClass,
                $entityClassName::TABLE_MAP,
                $entity,
            );

            $columnNamesForUpdateWithPdoPlaceholder = array_map(function ($columnName) use (&$keyIndex, $tableMapClass) {
                return sprintf('%s=:p%d', $this->quote($columnName, $tableMapClass), $keyIndex++);
            }, array_keys($valuesForUpdate));

            $values = array_merge(array_values($valuesForUpdate), array_values($idColumnValuesAndTypes));

            $whereClauses = [];

            foreach (array_keys($idColumnValuesAndTypes) as $primaryKeyColumnName) {
                $whereClauses[] = sprintf('%s.%s=:p%d', $tableMapClass->getName(), $primaryKeyColumnName, $keyIndex++);
            }

            $sql = sprintf(
                'UPDATE %s SET %s WHERE %s;',
                $tableMapClass->getName(),
                implode(', ', $columnNamesForUpdateWithPdoPlaceholder),
                implode(' AND ', $whereClauses),
            );

            $statement = $this->prepareStatement($sql, $connection);
            $statement = $this->bindUpdatePostgresValues($statement, $values);
            $statements[] = $statement;
        }

        return $statements;
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
     * @param \Propel\Runtime\Connection\StatementInterface $statement
     * @param array $values
     *
     * @return \Propel\Runtime\Connection\StatementInterface
     */
    protected function bindUpdateValues(StatementInterface $statement, array $values): StatementInterface
    {
        foreach ($values as $rowValues) {
            foreach ($rowValues as $index => $value) {
                $statement->bindValue(sprintf(':p%d', $index), $value['value'], $value['type']);
            }
        }

        return $statement;
    }

    /**
     * @param \Propel\Runtime\Connection\StatementInterface $statement
     * @param array $values
     *
     * @return \Propel\Runtime\Connection\StatementInterface
     */
    protected function bindUpdatePostgresValues(StatementInterface $statement, array $values): StatementInterface
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
     * @param array|string|float|int|bool $value
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return string|float|int|bool
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

    /**
     * @return bool
     */
    protected function requiresPrimaryKeyValue(): bool
    {
        return ($this->getAdapter() instanceof PgsqlAdapter);
    }
}
