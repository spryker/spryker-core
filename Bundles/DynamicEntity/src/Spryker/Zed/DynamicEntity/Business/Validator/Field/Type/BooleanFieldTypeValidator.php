<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Type;

use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;

class BooleanFieldTypeValidator extends AbstractFieldTypeValidator implements DynamicEntityValidatorInterface
{
    /**
     * @var string
     */
    protected const BOOLEAN_FIELD_TYPE = 'boolean';

    /**
     * @param mixed $fieldValue
     *
     * @return bool
     */
    public function isValidType(mixed $fieldValue): bool
    {
        return is_bool($fieldValue) === true;
    }

    /**
     * @param mixed $fieldValue
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     *
     * @return bool
     */
    public function isValidValue(mixed $fieldValue, DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer): bool
    {
        return $this->isValidType($fieldValue);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::BOOLEAN_FIELD_TYPE;
    }
}
