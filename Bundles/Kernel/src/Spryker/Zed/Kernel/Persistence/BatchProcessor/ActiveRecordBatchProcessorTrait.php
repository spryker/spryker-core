<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence\BatchProcessor;

use Exception;
use PDO;
use PDOStatement;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Adapter\AdapterInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\ColumnMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Propel;
use Throwable;

trait ActiveRecordBatchProcessorTrait
{
    /**
     * @var \Propel\Runtime\ActiveRecord\ActiveRecordInterface[][]
     */
    protected $entitiesToInsert = [];

    /**
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

        if ($entity->isNew()) {
            if (!isset($this->entitiesToInsert[$className])) {
                $this->entitiesToInsert[$className] = [];
            }

            $this->entitiesToInsert[$className][] = $entity;

            return;
        }

        if (!isset($this->entitiesToUpdate[$className])) {
            $this->entitiesToUpdate[$className] = [];
        }

        $this->entitiesToUpdate[$className][] = $entity;
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        foreach ($this->entitiesToInsert as $entityClassName => $entities) {
            $this->preSave($entities);
            $this->preInsert($entities);
            $statement = $this->buildInsertStatement($entityClassName, $entities);
            $this->executeStatement($statement, $entityClassName, 'insert');
            $this->postInsert($entities);
            $this->postSave($entities);
        }

        foreach ($this->entitiesToUpdate as $entityClassName => $entities) {
            $this->preSave($entities);
            $this->preUpdate($entities);
            $statement = $this->buildUpdateStatement($entityClassName, $entities);
            $this->executeStatement($statement, $entityClassName, 'update');
            $this->postUpdate($entities);
            $this->postSave($entities);
        }

        return true;
    }

    /**
     * @param array $entities
     *
     * @return void
     */
    protected function preSave(array $entities): void
    {
        foreach ($entities as $entity) {
            $entity->preSave();
        }
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
     * @param array $entities
     *
     * @return void
     */
    protected function preInsert(array $entities): void
    {
        foreach ($entities as $entity) {
            $entity->preInsert();
        }
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
     * @param array $entities
     *
     * @return void
     */
    protected function preUpdate(array $entities): void
    {
        foreach ($entities as $entity) {
            $entity->preUpdate();
        }
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
        } catch (Throwable $throwable) {
            $this->getConnection()->rollBack();

            throw new Exception(sprintf('Failed to execute %s statement for %s. Error: %s', $type, $entityClassName, $throwable->getMessage()), 0, $throwable);
        }
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

        $columnNames = [];

        foreach ($columnMapCollection as $column) {
            $columnNames[] = $this->quote($column->getName(), $tableMapClass);
        }

        $count = count($entities);
        $sql = sprintf('INSERT INTO %s (%s) VALUES ', $tableMapClass->getName(), implode(', ', $columnNames));
        $insertLines = [];

        $index = 0;
        for ($i = 0; $i < $count; $i++) {
            $columnNamesWithPdoPlaceholder = array_map(function (ColumnMap $columnMap) use (&$index, $tableMapClass) {
                if ($columnMap->isPrimaryKey() && $tableMapClass->getPrimaryKeyMethodInfo() !== null) {
                    return sprintf('(SELECT nextval(\'%s\'))', $tableMapClass->getPrimaryKeyMethodInfo());
                }

                return sprintf(':p%d', $index++);
            }, $columnMapCollection);

            $insertLines[] = sprintf('(%s)', implode(', ', $columnNamesWithPdoPlaceholder));
        }

        $sql .= implode(', ', $insertLines);

        $connection = Propel::getConnection();
        $statement = $connection->prepare($sql);

        $index = 0;

        foreach ($entities as $entity) {
            /** @var \Orm\Zed\Customer\Persistence\SpyCustomer $entity */
            foreach ($entity->toArray(TableMap::TYPE_FIELDNAME) as $fieldName => $value) {
                $columnMap = $columnMapCollection[strtoupper($fieldName)];

                if ($columnMap->isPrimaryKey() && $tableMapClass->getPrimaryKeyMethodInfo() !== null) {
                    continue;
                }

                $statement->bindValue(
                    sprintf(':p%d', $index++),
                    $this->getValue($columnMap, $tableMapClass, $value),
                    $columnMap->getPdoType()
                );
            }
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
     * @param string $entityClassName
     * @param array $entities
     *
     * @return \PDOStatement
     */
    protected function buildUpdateStatement(string $entityClassName, array $entities): PDOStatement
    {
        $tableMapClass = $this->getTableMapClass($entityClassName);
        $columnMapCollection = $tableMapClass->getColumns();

        $columnNamesForUpdate = [];
        $primaryKeyColumns = [];

        foreach ($columnMapCollection as $columnMap) {
            if ($columnMap->isPrimaryKey()) {
                $primaryKeyColumns[] = $columnMap;

                continue;
            }

            $columnNamesForUpdate[] = $columnMap->getName();
        }

        $count = count($entities);
        $sql = '';
        $index = 0;
        for ($i = 0; $i < $count; $i++) {
            $columnNameForUpdateWithPdoPlaceholder = array_map(function ($columnName) use (&$index, $tableMapClass) {
                return sprintf('%s=:p%d', $this->quote($columnName, $tableMapClass), $index++);
            }, $columnNamesForUpdate);

            $whereClauses = [];
            foreach ($primaryKeyColumns as $primaryKeyColumn) {
                $whereClauses[] = sprintf('%s.%s=:p%d', $tableMapClass->getName(), $primaryKeyColumn->getName(), $index++);
            }

            $sql .=
                sprintf(
                    'UPDATE %s SET %s WHERE %s;',
                    $tableMapClass->getName(),
                    implode(', ', $columnNameForUpdateWithPdoPlaceholder),
                    implode(' AND ', $whereClauses)
                );
        }

        $connection = Propel::getConnection();
        $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $statement = $connection->prepare($sql);

        $index = 0;

        foreach ($entities as $entity) {
            $idColumnValuesAndTypes = [];
            foreach ($entity->toArray(TableMap::TYPE_FIELDNAME) as $fieldName => $value) {
                $columnMap = $columnMapCollection[strtoupper($fieldName)];

                if ($columnMap->isPrimaryKey()) {
                    $idColumnValuesAndTypes[] = [
                        'value' => $value,
                        'type' => $columnMap->getPdoType(),
                    ];

                    continue;
                }

                $statement->bindValue(sprintf(':p%d', $index++), $this->getValue($columnMap, $tableMapClass, $value), $columnMap->getPdoType());
            }

            foreach ($idColumnValuesAndTypes as $idColumnValueAndType) {
                $statement->bindValue(sprintf(':p%d', $index++), $idColumnValueAndType['value'], $idColumnValueAndType['type']);
            }
        }

        return $statement;
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
