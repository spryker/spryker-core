<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlankValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AttributeFieldNotBlankValidator extends NotBlankValidator
{

    /**
     * @param string $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!($constraint instanceof AttributeFieldNotBlank)) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Attribute');
        }

        if ($this->shouldValidateAttributeValue($constraint)) {
            parent::validate($value, $constraint);
        }
    }

    /**
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Constraints\AttributeFieldNotBlank $constraint
     *
     * @return bool
     */
    protected function shouldValidateAttributeValue(AttributeFieldNotBlank $constraint)
    {
        $isAttributeSelected = $this->isAttributeGroupChecked($constraint->getAttributeCheckboxFieldName());

        return $isAttributeSelected;
    }

    /**
     * @param string $attributeName
     *
     * @return bool
     */
    protected function isAttributeGroupChecked($attributeName)
    {
        return (bool)$this->context->getRoot()
            ->get($attributeName)
            ->getData();
    }

}
