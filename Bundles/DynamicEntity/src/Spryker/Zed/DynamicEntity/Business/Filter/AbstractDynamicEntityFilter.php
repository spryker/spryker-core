<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Filter;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;

abstract class AbstractDynamicEntityFilter
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param array<mixed> $filteredFields
     *
     * @return array<mixed>
     */
    abstract public function filterFields(
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        array $filteredFields
    ): array;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityTransfer
     */
    public function filter(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer
    ): DynamicEntityTransfer {
        $filteredFields = [];
        $dynamicEntityDefinitionTransfer = $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail();

        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinitionTransfer) {
            $filteredFields = $this->filterFields(
                $fieldDefinitionTransfer,
                $dynamicEntityTransfer,
                $filteredFields,
            );
        }

        $dynamicEntityTransfer->setFields($filteredFields);

        return $dynamicEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     * @param array<mixed> $dynamicEntityFields
     * @param array<mixed> $filteredFields
     *
     * @return array<mixed>
     */
    protected function addDynamicEntityFieldToFilteredFields(
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer,
        array $dynamicEntityFields,
        array $filteredFields
    ): array {
        $filteredFields[$fieldDefinitionTransfer->getFieldVisibleName()] = $dynamicEntityFields[$fieldDefinitionTransfer->getFieldVisibleName()];

        return $filteredFields;
    }
}
