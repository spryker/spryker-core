<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Filter\Validator;

use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;

class DynamicEntityFieldCreationPreValidator extends AbstractDynamicEntityPreValidator implements DynamicEntityPreValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     * @param array<mixed> $dynamicEntityFields
     * @param callable $filterCallback
     * @param string $identifier
     *
     * @return bool
     */
    public function isFieldNonModifiable(
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer,
        array $dynamicEntityFields,
        callable $filterCallback,
        string $identifier
    ): bool {
        return call_user_func($filterCallback, $fieldDefinitionTransfer) === false &&
            isset($dynamicEntityFields[$fieldDefinitionTransfer->getFieldVisibleNameOrFail()]);
    }
}
