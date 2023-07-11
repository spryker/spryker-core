<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Filter;

use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Map\ColumnMap;

class DynamicEntityFieldUpdateFilter extends AbstractDynamicEntityFilter implements DynamicEntityFilterInterface
{
    /**
     * @var string
     */
    protected const IDENTIFIER = 'identifier';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param array<mixed> $filteredFields
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord
     *
     * @return array<mixed>
     */
    public function filterFields(
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        array $filteredFields,
        ActiveRecordInterface $activeRecord
    ): array {
        $dynamicEntityFields = $dynamicEntityTransfer->getFields();

        if (isset($dynamicEntityFields[$fieldDefinitionTransfer->getFieldVisibleNameOrFail()])) {
            return $this->addDynamicEntityFieldToFilteredFields($fieldDefinitionTransfer, $dynamicEntityFields, $filteredFields);
        }

        if (isset($dynamicEntityFields[static::IDENTIFIER])) {
            return $filteredFields;
        }

        if ($fieldDefinitionTransfer->getValidationOrFail()->getIsRequired() === false) {
            return $filteredFields;
        }

        $columnConfiguration = $this->getColumnConfiguration($activeRecord, $fieldDefinitionTransfer->getFieldNameOrFail());

        if ($columnConfiguration->isNotNull() === false) {
            return $this->addDefaultValuesForMissingResources($fieldDefinitionTransfer, $filteredFields);
        }

        return $filteredFields;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $activeRecord
     * @param string $fieldName
     *
     * @return \Propel\Runtime\Map\ColumnMap
     */
    protected function getColumnConfiguration(ActiveRecordInterface $activeRecord, string $fieldName): ColumnMap
    {
        $tableMapClass = $activeRecord::TABLE_MAP;
        $entityTableMap = new $tableMapClass();

        return $entityTableMap->getColumn($fieldName);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     * @param array<mixed> $filteredFields
     *
     * @return array<mixed>
     */
    protected function addDefaultValuesForMissingResources(
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer,
        array $filteredFields
    ): array {
        $filteredFields[$fieldDefinitionTransfer->getFieldVisibleNameOrFail()] = null;

        return $filteredFields;
    }
}
