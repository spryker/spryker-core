<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DynamicEntityGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\DynamicEntityConfigurationConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Propel\Runtime\Map\DatabaseMap;
use Spryker\Zed\DynamicEntityGui\Communication\Form\UpdateDynamicDataConfigurationForm;
use Spryker\Zed\DynamicEntityGui\Communication\Validator\TableValidatorInterface;
use Spryker\Zed\DynamicEntityGui\Dependency\Facade\DynamicEntityGuiToDynamicEntityFacadeInterface;

class UpdateDynamicDataConfigurationFormDataProvider
{
    /**
     * @param \Spryker\Zed\DynamicEntityGui\Dependency\Facade\DynamicEntityGuiToDynamicEntityFacadeInterface $dynamicEntityFacade
     * @param \Propel\Runtime\Map\DatabaseMap $databaseMap
     * @param \Spryker\Zed\DynamicEntityGui\Communication\Validator\TableValidatorInterface $tableValidator
     */
    public function __construct(
        protected DynamicEntityGuiToDynamicEntityFacadeInterface $dynamicEntityFacade,
        protected DatabaseMap $databaseMap,
        protected TableValidatorInterface $tableValidator
    ) {
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

        $dynamicEntityConfigurationTransfers = $this->getDynamicEntityConfigurations($tableName);

        /** @var \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer */
        $dynamicEntityConfigurationTransfer = $dynamicEntityConfigurationTransfers->offsetGet(0);

        return $this->mapDynamicEntityConfigurationsToData($dynamicEntityConfigurationTransfer);
    }

    /**
     * @param string $tableName
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    protected function getDynamicEntityConfigurations(string $tableName): ArrayObject
    {
        $dynamicEntityConfigurationCriteriaTransfer = (new DynamicEntityConfigurationCriteriaTransfer())
            ->setDynamicEntityConfigurationConditions(
                (new DynamicEntityConfigurationConditionsTransfer())
                    ->setTableName($tableName),
            );

        return $this->dynamicEntityFacade
            ->getDynamicEntityConfigurationCollection($dynamicEntityConfigurationCriteriaTransfer)
            ->getDynamicEntityConfigurations();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array
     */
    protected function mapDynamicEntityConfigurationsToData(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): array {
        $dynamicEntityConfiguration = $dynamicEntityConfigurationTransfer->toArray();
        $dynamicEntityConfiguration += $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->toArray();

        $fieldDefinitions = [];

        foreach ($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions() as $fieldDefinitionTransfer) {
            $validation = $fieldDefinitionTransfer->getValidationOrFail()->toArray();

            $fieldDefinition = $fieldDefinitionTransfer->toArray();

            // Flatten the fieldDefinition and validation rules into one array for form rendering
            $fieldDefinitions[] = array_merge($fieldDefinition, $validation);
        }

        $dynamicEntityConfiguration['field_definitions'] = $fieldDefinitions;

        return $dynamicEntityConfiguration;
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
}
