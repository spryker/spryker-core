<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\DynamicEntityConfigurationConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Propel\Generator\Model\PropelTypes;
use Propel\Runtime\Map\ColumnMap;
use Propel\Runtime\Map\DatabaseMap;
use Spryker\Zed\DynamicEntityGui\Communication\Form\UpdateDynamicDataConfigurationForm;
use Spryker\Zed\DynamicEntityGui\Communication\Validator\TableValidatorInterface;
use Spryker\Zed\DynamicEntityGui\Dependency\Facade\DynamicEntityGuiToDynamicEntityFacadeInterface;

class UpdateDynamicDataConfigurationFormDataProvider
{
    /**
     * @var string
     */
    protected const TABLE_NAME = 'table_name';

    /**
     * @var string
     */
    protected const TABLE_ALIAS = 'table_alias';

    /**
     * @var string
     */
    protected const IS_ACTIVE = 'is_active';

    /**
     * @var string
     */
    protected const IS_DELETABLE = 'is_deletable';

    /**
     * @var string
     */
    protected const IDENTIFIER = 'identifier';

    /**
     * @var string
     */
    protected const FIELD_NAME = 'field_name';

    /**
     * @var string
     */
    protected const FIELD_VISIBLE_NAME = 'field_visible_name';

    /**
     * @var string
     */
    protected const TYPE = 'type';

    /**
     * @var string
     */
    protected const IS_CREATABLE = 'is_creatable';

    /**
     * @var string
     */
    protected const IS_EDITABLE = 'is_editable';

    /**
     * @var string
     */
    protected const IS_REQUIRED = 'is_required';

    /**
     * @var string
     */
    protected const MIN = 'min';

    /**
     * @var string
     */
    protected const MAX = 'max';

    /**
     * @var string
     */
    protected const MIN_LENGTH = 'min_length';

    /**
     * @var string
     */
    protected const MAX_LENGTH = 'max_length';

    /**
     * @var string
     */
    protected const SCALE = 'scale';

    /**
     * @var string
     */
    protected const PRECISION = 'precision';

    /**
     * @var string
     */
    protected const FIELD_DEFINITIONS = 'field_definitions';

    /**
     * @var string
     */
    protected const DYNAMIC_ENTITY_DEFINITION = 'dynamic_entity_definition';

    /**
     * @var string
     */
    protected const IS_ENABLED = 'is_enabled';

    /**
     * @var string
     */
    protected const VALIDATION = 'validation';

    /**
     * @var string
     */
    protected const FIELD_DEFINTION_TYPE_STRING = 'string';

    /**
     * @var string
     */
    protected const FIELD_DEFINTION_TYPE_INTEGER = 'integer';

    /**
     * @var string
     */
    protected const FIELD_DEFINTION_TYPE_DECIMAL = 'decimal';

    /**
     * @var string
     */
    protected const FIELD_DEFINTION_TYPE_BOOLEAN = 'boolean';

    /**
     * @var \Spryker\Zed\DynamicEntityGui\Dependency\Facade\DynamicEntityGuiToDynamicEntityFacadeInterface
     */
    protected DynamicEntityGuiToDynamicEntityFacadeInterface $dynamicEntityFacade;

    /**
     * @var \Propel\Runtime\Map\DatabaseMap
     */
    protected DatabaseMap $databaseMap;

    /**
     * @var \Spryker\Zed\DynamicEntityGui\Communication\Validator\TableValidatorInterface
     */
    protected TableValidatorInterface $tableValidator;

    /**
     * @param \Spryker\Zed\DynamicEntityGui\Dependency\Facade\DynamicEntityGuiToDynamicEntityFacadeInterface $dynamicEntityFacade
     * @param \Propel\Runtime\Map\DatabaseMap $databaseMap
     * @param \Spryker\Zed\DynamicEntityGui\Communication\Validator\TableValidatorInterface $tableValidator
     */
    public function __construct(
        DynamicEntityGuiToDynamicEntityFacadeInterface $dynamicEntityFacade,
        DatabaseMap $databaseMap,
        TableValidatorInterface $tableValidator
    ) {
        $this->dynamicEntityFacade = $dynamicEntityFacade;
        $this->databaseMap = $databaseMap;
        $this->tableValidator = $tableValidator;
    }

    /**
     * @param string $tableName
     *
     * @return array<string, array<\Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>>
     */
    public function getOptions(string $tableName): array
    {
        return [
            UpdateDynamicDataConfigurationForm::OPTION_TABLE_COLUMNS => $this->getTableColumns($tableName),
        ];
    }

    /**
     * @param string $tableName
     *
     * @return array<mixed>|null
     */
    public function getData(string $tableName): ?array
    {
        if ($this->tableValidator->validateIsTableDisallowed($tableName) === true) {
            return null;
        }

        if ($this->tableValidator->validateIsTableExist($tableName) === false) {
            return null;
        }

        $dynamicEntityConfigurations = $this->getDynamicEntityConfigurations($tableName);
        $dynamicEntityConfigurations = $this->mapDynamicEntityConfigurationsToData($dynamicEntityConfigurations, $tableName);

        return $dynamicEntityConfigurations;
    }

    /**
     * @param string $tableName
     *
     * @return array<mixed>
     */
    protected function getDynamicEntityConfigurations(string $tableName): array
    {
        $dynamicEntityConfigurationCriteriaTransfer = (new DynamicEntityConfigurationCriteriaTransfer())
            ->setDynamicEntityConfigurationConditions(
                (new DynamicEntityConfigurationConditionsTransfer())
                    ->setTableName($tableName),
            );

        $dynamicEntityConfigurationsTransfer = $this->dynamicEntityFacade
            ->getDynamicEntityConfigurationCollection($dynamicEntityConfigurationCriteriaTransfer)
            ->getDynamicEntityConfigurations();

        if ($dynamicEntityConfigurationsTransfer->count() === 0) {
            return [];
        }

        return $dynamicEntityConfigurationsTransfer->offsetGet(0)->toArray();
    }

    /**
     * @param array<mixed> $dynamicEntityConfigurations
     * @param string $tableName
     *
     * @return array<mixed>
     */
    protected function mapDynamicEntityConfigurationsToData(array $dynamicEntityConfigurations, string $tableName): array
    {
        $dynamicEntityConfigurations[static::TABLE_NAME] = $tableName;
        $dynamicEntityConfigurations[static::TABLE_ALIAS] = $dynamicEntityConfigurations[static::TABLE_ALIAS];
        $dynamicEntityConfigurations[static::IS_ACTIVE] = $dynamicEntityConfigurations[static::IS_ACTIVE];
        $dynamicEntityConfigurations[static::IS_DELETABLE] = $dynamicEntityConfigurations[static::DYNAMIC_ENTITY_DEFINITION][static::IS_DELETABLE] ?? false;
        $dynamicEntityConfigurations[static::IDENTIFIER] = $dynamicEntityConfigurations[static::DYNAMIC_ENTITY_DEFINITION][static::IDENTIFIER] ?? null;

        $databaseTable = $this->databaseMap->getTable($tableName);
        $fieldDefinitions = $this->indexFieldsDefinitionValues($dynamicEntityConfigurations);

        foreach ($databaseTable->getColumns() as $column) {
            $dynamicEntityConfigurations[static::FIELD_DEFINITIONS][] = [
                static::FIELD_NAME => $column->getName(),
                static::FIELD_VISIBLE_NAME => $fieldDefinitions[$column->getName()][static::FIELD_VISIBLE_NAME] ?? lcfirst($column->getPhpName()),
                static::TYPE => $fieldDefinitions[$column->getName()][static::TYPE] ?? $this->getDefaultTypeColumn($column),
                static::IS_CREATABLE => $fieldDefinitions[$column->getName()][static::IS_CREATABLE] ?? false,
                static::IS_EDITABLE => $fieldDefinitions[$column->getName()][static::IS_EDITABLE] ?? false,
                static::IS_REQUIRED => $fieldDefinitions[$column->getName()][static::VALIDATION][static::IS_REQUIRED] ?? $this->getDefaultIsRequiredColumn($column),
                static::MIN => $fieldDefinitions[$column->getName()][static::VALIDATION][static::MIN] ?? null,
                static::MAX => $fieldDefinitions[$column->getName()][static::VALIDATION][static::MAX] ?? null,
                static::MIN_LENGTH => $fieldDefinitions[$column->getName()][static::VALIDATION][static::MIN_LENGTH] ?? null,
                static::MAX_LENGTH => $fieldDefinitions[$column->getName()][static::VALIDATION][static::MAX_LENGTH] ?? null,
                static::SCALE => $fieldDefinitions[$column->getName()][static::VALIDATION][static::SCALE] ?? null,
                static::PRECISION => $fieldDefinitions[$column->getName()][static::VALIDATION][static::PRECISION] ?? null,
                static::IS_ENABLED => isset($fieldDefinitions[$column->getName()]) ? true : false,
            ];
        }

        return $dynamicEntityConfigurations;
    }

    /**
     * @param array<mixed> $dynamicEntityConfigurations
     *
     * @return array<mixed>
     */
    protected function indexFieldsDefinitionValues(array $dynamicEntityConfigurations): array
    {
        $indexedDefinitions = [];

        if (!isset($dynamicEntityConfigurations[static::DYNAMIC_ENTITY_DEFINITION])) {
            return [];
        }

        foreach ($dynamicEntityConfigurations[static::DYNAMIC_ENTITY_DEFINITION][static::FIELD_DEFINITIONS] as $fieldDefinition) {
            $indexedDefinitions[$fieldDefinition[static::FIELD_NAME]] = $fieldDefinition;
        }

        return $indexedDefinitions;
    }

    /**
     * @param string $tableName
     *
     * @return array<mixed>
     */
    protected function getTableColumns(string $tableName): array
    {
        $columns = [];

        $databaseTable = $this->databaseMap->getTable($tableName);

        foreach ($databaseTable->getColumns() as $column) {
            $columns[$column->getName()] = $column->getName();
        }

        return $columns;
    }

    /**
     * @param \Propel\Runtime\Map\ColumnMap $column
     *
     * @return string
     */
    protected function getDefaultTypeColumn(ColumnMap $column): string
    {
        return $this->mapPropelTypesToFieldType($column->getType());
    }

    /**
     * @param \Propel\Runtime\Map\ColumnMap $column
     *
     * @return bool
     */
    protected function getDefaultIsRequiredColumn(ColumnMap $column): bool
    {
        return (bool)$column->isNotNull();
    }

    /**
     * @param string $propelType
     *
     * @return string
     */
    protected function mapPropelTypesToFieldType(string $propelType): string
    {
        $propelTypeToTypeMap = [
            PropelTypes::VARCHAR => static::FIELD_DEFINTION_TYPE_STRING,
            PropelTypes::LONGVARCHAR => static::FIELD_DEFINTION_TYPE_STRING,
            PropelTypes::CLOB => static::FIELD_DEFINTION_TYPE_STRING,
            PropelTypes::CHAR => static::FIELD_DEFINTION_TYPE_STRING,
            PropelTypes::INTEGER => static::FIELD_DEFINTION_TYPE_INTEGER,
            PropelTypes::BOOLEAN => static::FIELD_DEFINTION_TYPE_BOOLEAN,
            PropelTypes::FLOAT => static::FIELD_DEFINTION_TYPE_STRING,
            PropelTypes::DOUBLE => static::FIELD_DEFINTION_TYPE_DECIMAL,
            PropelTypes::DECIMAL => static::FIELD_DEFINTION_TYPE_DECIMAL,
            PropelTypes::DATE => static::FIELD_DEFINTION_TYPE_STRING,
            PropelTypes::TIME => static::FIELD_DEFINTION_TYPE_STRING,
            PropelTypes::TIMESTAMP => static::FIELD_DEFINTION_TYPE_STRING,
            PropelTypes::ENUM => static::FIELD_DEFINTION_TYPE_STRING,
        ];

        return $propelTypeToTypeMap[$propelType] ?? static::FIELD_DEFINTION_TYPE_STRING;
    }
}
