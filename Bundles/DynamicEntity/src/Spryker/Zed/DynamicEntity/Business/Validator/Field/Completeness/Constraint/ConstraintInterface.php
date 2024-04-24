<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness\Constraint;

use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;

interface ConstraintInterface
{
    /**
     * @param string $constraintName
     *
     * @return bool
     */
    public function isApplicable(string $constraintName): bool;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
     *
     * @return bool
     */
    public function isValid(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityFieldDefinitionTransfer $fieldDefinitionTransfer
    ): bool;

    /**
     * @return string
     */
    public function getErrorMessage(): string;
}
