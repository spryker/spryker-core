<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Builder;

use Propel\Generator\Model\Column;
use Propel\Generator\Model\Database;
use Propel\Generator\Model\ForeignKey;
use Propel\Generator\Model\IdMethod;
use Propel\Generator\Model\IdMethodParameter;
use Propel\Generator\Model\Table;
use Propel\Generator\Model\Unique;
use Spryker\Service\AclEntity\AclEntityServiceInterface;

class ConnectorTableBuilder implements ConnectorTableBuilderInterface
{
    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_TABLE_NAME = 'spy_acl_entity_segment';

    /**
     * @var string
     */
    protected const ID_COLUMN_TYPE = 'INTEGER';

    /**
     * @var string
     */
    protected const FOREIGN_KEY_TEMPLATE = '%s-%s';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_CONNECTOR_TABLE_UNIQUE_KEY_NAME = 'fk_target_entity_unique_fk_acl_entity_segment';

    /**
     * @var string
     */
    protected const ID_METHOD_PARAMETER_VALUE_TEMPLATE = '%s_seq';

    /**
     * @var \Propel\Generator\Model\Database
     */
    protected $database;

    /**
     * @var \Spryker\Service\AclEntity\AclEntityServiceInterface
     */
    protected $aclEntityService;

    /**
     * @var \Propel\Generator\Model\Table
     */
    protected $baseTable;

    /**
     * @param \Propel\Generator\Model\Table $baseTable
     * @param \Propel\Generator\Model\Database $database
     * @param \Spryker\Service\AclEntity\AclEntityServiceInterface $aclEntityService
     */
    public function __construct(
        Table $baseTable,
        Database $database,
        AclEntityServiceInterface $aclEntityService
    ) {
        $this->baseTable = $baseTable;
        $this->database = $database;
        $this->aclEntityService = $aclEntityService;
    }

    /**
     * @param \Propel\Generator\Model\Table $table
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Builder\ConnectorTableBuilderInterface
     */
    public function setBaseTable(Table $table): ConnectorTableBuilderInterface
    {
        $this->baseTable = $table;

        return $this;
    }

    /**
     * @return \Propel\Generator\Model\Table
     */
    public function build(): Table
    {
        $connectorTableName = $this->aclEntityService->generateSegmentConnectorTableName($this->baseTable->getName());
        if ($this->database->hasTable($connectorTableName)) {
            /** @var \Propel\Generator\Model\Table $table */
            $table = $this->database->getTable($connectorTableName);

            return $table;
        }

        $table = new Table($connectorTableName);
        $table->setIdMethod(IdMethod::NATIVE);
        $table->setDatabase($this->database);
        $table->setNamespace('\\' . $this->baseTable->getNamespace());
        $table->setPackage($this->baseTable->getPackage());
        $table = $this->addIdMethodParameter($table, $connectorTableName);

        $idColumn = $this->buildIdColumn($connectorTableName);

        $table->addColumn($idColumn);
        $table = $this->database->addTable($table);

        $table = $this->addTargetTableForeignKey($table);
        $table = $this->addAclEntitySegmentTableForeignKey($table);
        $table = $this->addUniqueForeignKeysConstraint($table);

        return $table;
    }

    /**
     * @param string $connectorTableName
     *
     * @return \Propel\Generator\Model\Column
     */
    protected function buildIdColumn(string $connectorTableName): Column
    {
        $idColumn = new Column(
            $this->aclEntityService->generateSegmentConnectorTableIdColumnName($connectorTableName),
        );
        $idColumn->setPrimaryKey(true);
        $idColumn->setNotNull(true);
        $idColumn->setType(static::ID_COLUMN_TYPE);
        $idColumn->setAutoIncrement(true);
        $idColumn->getDomain()->setSqlType(static::ID_COLUMN_TYPE);

        return $idColumn;
    }

    /**
     * @param \Propel\Generator\Model\Table $table
     *
     * @return \Propel\Generator\Model\Table
     */
    protected function addTargetTableForeignKey(Table $table): Table
    {
        $columnName = $this->aclEntityService->generateSegmentConnectorReferenceColumnName(
            $this->baseTable->getName(),
        );

        $column = $this->addTargetTableColumn($columnName);
        $table->addColumn($column);

        $constraint = $this->addTargetTableConstraint($table->getName(), $columnName);
        $constraint->setOnDelete(ForeignKey::CASCADE);
        $table->addForeignKey($constraint);

        return $table;
    }

    /**
     * @param \Propel\Generator\Model\Table $table
     *
     * @return \Propel\Generator\Model\Table
     */
    protected function addAclEntitySegmentTableForeignKey(Table $table): Table
    {
        /** @var \Propel\Generator\Model\Table $aclEntitySegmentTable */
        $aclEntitySegmentTable = $this->database->getTable(static::ACL_ENTITY_SEGMENT_TABLE_NAME);

        $columnName = $this->aclEntityService
            ->generateSegmentConnectorReferenceColumnName($aclEntitySegmentTable->getName());

        $column = $this->addAclEntityColumn($columnName, $aclEntitySegmentTable);
        $table->addColumn($column);

        $constraint = $this->addAclEntityConstraint($table->getName(), $columnName, $aclEntitySegmentTable);
        $constraint->setOnDelete(ForeignKey::CASCADE);
        $table->addForeignKey($constraint);

        return $table;
    }

    /**
     * @param \Propel\Generator\Model\Table $table
     *
     * @return \Propel\Generator\Model\Table
     */
    protected function addUniqueForeignKeysConstraint(Table $table): Table
    {
        $fkTargetEntityColumnName = $this->aclEntityService->generateSegmentConnectorReferenceColumnName(
            $this->baseTable->getName(),
        );

        /** @var \Propel\Generator\Model\Column $fkTargetEntityColumn */
        $fkTargetEntityColumn = $table->getColumn($fkTargetEntityColumnName);
        $unique = new Unique();
        $unique->setName($this->aclEntityService->generateSegmentConnectorTableUniqueConstraintName($table->getName(), $fkTargetEntityColumn->getName()));

        /** @var \Propel\Generator\Model\Table $aclEntitySegmentTable */
        $aclEntitySegmentTable = $this->database->getTable(static::ACL_ENTITY_SEGMENT_TABLE_NAME);
        $fkAclEntitySegmentColumnName = $this->aclEntityService
            ->generateSegmentConnectorReferenceColumnName($aclEntitySegmentTable->getName());

        $fkAclEntitySegmentColumn = $table->getColumn($fkAclEntitySegmentColumnName);

        $unique->setColumns([$fkTargetEntityColumn, $fkAclEntitySegmentColumn]);

        $table->addUnique($unique);

        return $table;
    }

    /**
     * @param string $columnName
     *
     * @return \Propel\Generator\Model\Column
     */
    protected function addTargetTableColumn(string $columnName): Column
    {
        $column = new Column($columnName);
        /** @var \Propel\Generator\Model\Column $primaryKeyColumn */
        $primaryKeyColumn = $this->baseTable->getAutoIncrementPrimaryKey();
        $column->setType($primaryKeyColumn->getType());
        $column->setNotNull(true);
        $column->getDomain()->setSqlType(
            $primaryKeyColumn->getType(),
        );

        return $column;
    }

    /**
     * @param string $tableName
     * @param string $columnName
     *
     * @return \Propel\Generator\Model\ForeignKey
     */
    protected function addTargetTableConstraint(string $tableName, string $columnName): ForeignKey
    {
        $constraint = new ForeignKey(sprintf(static::FOREIGN_KEY_TEMPLATE, $tableName, $columnName));
        $constraint->setForeignTableCommonName($this->baseTable->getCommonName());
        $constraint->setForeignSchemaName($this->baseTable->getSchema());
        /** @var \Propel\Generator\Model\Column $primaryKeyColumn */
        $primaryKeyColumn = $this->baseTable->getAutoIncrementPrimaryKey();
        $constraint->addReference($columnName, $primaryKeyColumn->getName());

        return $constraint;
    }

    /**
     * @param string $columnName
     * @param \Propel\Generator\Model\Table $aclEntitySegmentTable
     *
     * @return \Propel\Generator\Model\Column
     */
    protected function addAclEntityColumn(string $columnName, Table $aclEntitySegmentTable): Column
    {
        /** @var \Propel\Generator\Model\Column $autoIncrementPrimaryKey */
        $autoIncrementPrimaryKey = $aclEntitySegmentTable->getAutoIncrementPrimaryKey();

        $column = new Column($columnName);
        $column->setType($autoIncrementPrimaryKey->getType());
        $column->setNotNull(true);
        $column->getDomain()->setSqlType($autoIncrementPrimaryKey->getType());

        return $column;
    }

    /**
     * @param string $tableName
     * @param string $columnName
     * @param \Propel\Generator\Model\Table $aclEntitySegmentTable
     *
     * @return \Propel\Generator\Model\ForeignKey
     */
    protected function addAclEntityConstraint(
        string $tableName,
        string $columnName,
        Table $aclEntitySegmentTable
    ): ForeignKey {
        /** @var \Propel\Generator\Model\Column $autoIncrementPrimaryKey */
        $autoIncrementPrimaryKey = $aclEntitySegmentTable->getAutoIncrementPrimaryKey();
        $constraint = new ForeignKey(sprintf(static::FOREIGN_KEY_TEMPLATE, $tableName, $columnName));
        $constraint->setForeignTableCommonName($aclEntitySegmentTable->getCommonName());
        $constraint->setForeignSchemaName($aclEntitySegmentTable->getSchema());
        $constraint->addReference($columnName, $autoIncrementPrimaryKey->getName());

        return $constraint;
    }

    /**
     * @param \Propel\Generator\Model\Table $table
     * @param string $connectorTableName
     *
     * @return \Propel\Generator\Model\Table
     */
    protected function addIdMethodParameter(Table $table, string $connectorTableName): Table
    {
        $idMethodParameter = new IdMethodParameter();
        $idMethodParameter->setValue(sprintf(
            static::ID_METHOD_PARAMETER_VALUE_TEMPLATE,
            $connectorTableName,
        ));
        $table->addIdMethodParameter($idMethodParameter);

        return $table;
    }
}
