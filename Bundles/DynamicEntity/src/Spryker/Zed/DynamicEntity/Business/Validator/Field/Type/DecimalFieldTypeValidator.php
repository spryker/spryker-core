<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Field\Type;

use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;

class DecimalFieldTypeValidator extends AbstractFieldTypeValidator implements DynamicEntityValidatorInterface
{
    /**
     * @var string
     */
    protected const REGEX_DECIMAL = '/^-?\d+(\.\d+)?$/';

    /**
     * @var string
     */
    protected const INTEGER_FIELD_TYPE = 'decimal';

    /**
     * @param mixed $fieldValue
     *
     * @return bool
     */
    public function isValidType(mixed $fieldValue): bool
    {
        if (!preg_match(static::REGEX_DECIMAL, $fieldValue)) {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $fieldValue
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     *
     * @return bool
     */
    public function isValidValue(mixed $fieldValue, DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer): bool
    {
        $validation = $dynamicEntityFieldDefinitionTransfer->getValidation();

        if ($validation === null || $validation->getPrecision() === null) {
            return true;
        }

        return $this->validateDecimal($fieldValue, $validation->getPrecision(), $validation->getScale() ?? 0);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::INTEGER_FIELD_TYPE;
    }

    /**
     * @param string $decimalValue
     * @param int $precision
     * @param int $scale
     *
     * @return bool
     */
    protected function validateDecimal(string $decimalValue, int $precision, int $scale): bool
    {
        if (!preg_match(static::REGEX_DECIMAL, $decimalValue)) {
            return false;
        }

        $parts = explode('.', $decimalValue);
        $integerPart = ltrim($parts[0], '0');
        $fractionalPart = isset($parts[1]) ? rtrim($parts[1], '0') : '';

        if (strlen($integerPart) > $precision - $scale || strlen($fractionalPart) > $scale) {
            return false;
        }

        return true;
    }
}
