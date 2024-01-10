<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Installer\Validator;

use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Propel\Runtime\Map\DatabaseMap;
use Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityFieldNotFoundException;

class FieldMappingValidator implements FieldMappingValidatorInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_FIELD_NOT_FOUND = 'Field `%s` not found in table `%s`';

    /**
     * @var string
     */
    protected const KEY_RELATION_FIELD_MAPPINGS = 'relation_field_mappings';

    /**
     * @var string
     */
    protected const KEY_PARENT_FIELD_NAME = 'parent_field_name';

    /**
     * @var string
     */
    protected const KEY_CHILD_FIELD_NAME = 'child_field_name';

    /**
     * @param \Propel\Runtime\Map\DatabaseMap $databaseMap
     */
    public function __construct(protected DatabaseMap $databaseMap)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $childDynamicEntityConfigurationCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $parentDynamicEntityConfigurationTransfer
     * @param array<string, array<string, mixed>> $indexedChildRelations
     *
     * @return void
     */
    public function validate(
        DynamicEntityConfigurationCollectionTransfer $childDynamicEntityConfigurationCollectionTransfer,
        DynamicEntityConfigurationTransfer $parentDynamicEntityConfigurationTransfer,
        array $indexedChildRelations
    ): void {
        foreach ($childDynamicEntityConfigurationCollectionTransfer->getDynamicEntityConfigurations() as $childDynamicEntityConfigurationTransfer) {
            $this->validateTableFields(
                $indexedChildRelations[$childDynamicEntityConfigurationTransfer->getTableAliasOrFail()][static::KEY_RELATION_FIELD_MAPPINGS][0][static::KEY_CHILD_FIELD_NAME],
                $childDynamicEntityConfigurationTransfer->getTableNameOrFail(),
            );
            $this->validateTableFields(
                $indexedChildRelations[$childDynamicEntityConfigurationTransfer->getTableAliasOrFail()][static::KEY_RELATION_FIELD_MAPPINGS][0][static::KEY_PARENT_FIELD_NAME],
                $parentDynamicEntityConfigurationTransfer->getTableNameOrFail(),
            );
        }
    }

    /**
     * @param string $fieldName
     * @param string $tableName
     *
     * @throws \Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityFieldNotFoundException
     *
     * @return void
     */
    protected function validateTableFields(
        string $fieldName,
        string $tableName
    ): void {
        $table = $this->databaseMap->getTable($tableName);

        if ($table->hasColumn($fieldName)) {
            return;
        }

        throw new DynamicEntityFieldNotFoundException(
            sprintf(static::MESSAGE_FIELD_NOT_FOUND, $fieldName, $tableName),
        );
    }
}
