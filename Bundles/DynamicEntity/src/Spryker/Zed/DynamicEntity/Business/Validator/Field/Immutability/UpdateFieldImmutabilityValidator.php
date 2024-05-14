<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Immutability;

use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;

class UpdateFieldImmutabilityValidator extends AbstractFieldImmutabilityValidator implements DynamicEntityValidatorInterface
{
    /**
     * @var string
     */
    protected const IDENTIFIER = 'identifier';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     * @param array<mixed> $dynamicEntityFields
     * @param string $identifier
     *
     * @return bool
     */
    public function isFieldNonModifiable(
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer,
        array $dynamicEntityFields,
        string $identifier
    ): bool {
        if (
            !isset($dynamicEntityFields[static::IDENTIFIER]) &&
            $fieldDefinitionTransfer->getFieldVisibleNameOrFail() === $identifier
        ) {
            return false;
        }

        foreach ($dynamicEntityFields as $fieldName => $fieldValue) {
            if ($fieldDefinitionTransfer->getFieldVisibleNameOrFail() !== $fieldName) {
                continue;
            }

            if ($fieldDefinitionTransfer->getIsEditableOrFail() === false) {
                return true;
            }
        }

        return false;
    }
}
