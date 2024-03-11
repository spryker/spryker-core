<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Indexer;

use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;

class DynamicEntityIndexer implements DynamicEntityIndexerInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param array<string, string> $indexedFieldDefinitions
     *
     * @return array<string, string>
     */
    public function getFieldValuesIndexedByFieldName(
        DynamicEntityTransfer $dynamicEntityTransfer,
        array $indexedFieldDefinitions
    ): array {
        $dynamicEntityFields = [];

        foreach ($dynamicEntityTransfer->getFields() as $fieldName => $fieldValue) {
            $dynamicEntityFields[$indexedFieldDefinitions[$fieldName]] = $fieldValue;
        }

        return $dynamicEntityFields;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return array<string, string>
     */
    public function getFieldNamesIndexedByFieldVisibleName(
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
    ): array {
        $fieldNamesIndexedByFieldVisibleNames = [];

        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinition) {
            $fieldNamesIndexedByFieldVisibleNames[$fieldDefinition->getFieldVisibleNameOrFail()] = $fieldDefinition->getFieldNameOrFail();
        }

        return $fieldNamesIndexedByFieldVisibleNames;
    }
}
