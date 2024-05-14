<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Immutability;

use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;

class CreationFieldImmutabilityValidator extends AbstractFieldImmutabilityValidator implements DynamicEntityValidatorInterface
{
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
        return $fieldDefinitionTransfer->getIsCreatableOrFail() === false &&
            isset($dynamicEntityFields[$fieldDefinitionTransfer->getFieldVisibleNameOrFail()]);
    }
}
