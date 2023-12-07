<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Filter;

use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

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

        if (array_key_exists($fieldDefinitionTransfer->getFieldVisibleNameOrFail(), $dynamicEntityFields)) {
            return $this->addDynamicEntityFieldToFilteredFields($fieldDefinitionTransfer, $dynamicEntityFields, $filteredFields);
        }

        return $filteredFields;
    }
}
