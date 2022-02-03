<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Spryker\Zed\ProductOption\ProductOptionConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueValueValidator extends ConstraintValidator
{
    /**
     * @var array
     */
    protected $validatedValues = [];

    /**
     * Checks if the passed value is valid.
     *
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer|mixed $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (in_array($value->getValue(), $this->validatedValues)) {
            $this->addUniqueViolationMessage();
        }

        $optionValue = $value->getValue();

        if (!$constraint instanceof UniqueValue) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\UniqueValue');
        }

        if (!$this->hasTranslationPrefix($optionValue)) {
            $optionValue = $this->addTranslationPrefix($optionValue);
        }

        if (!$this->isValueChanged($optionValue, $constraint, $value->getIdProductOptionValue())) {
            return;
        }

        if (!$this->isUniqueValue($optionValue, $constraint)) {
            $this->addUniqueViolationMessage();
        }

        $this->validatedValues[] = $value->getValue();
    }

    /**
     * @return void
     */
    protected function addUniqueViolationMessage()
    {
        $this->context
            ->buildViolation('Product option with this value is already used.')
            ->atPath(ProductOptionValueTransfer::VALUE)
            ->addViolation();
    }

    /**
     * @param string $value
     * @param \Spryker\Zed\ProductOption\Communication\Form\Constraint\UniqueValue $constraint
     *
     * @return bool
     */
    protected function isUniqueValue($value, UniqueValue $constraint)
    {
        $numberOfDiscounts = $constraint->getProductOptionQueryContainer()
            ->queryProductOptionValue($value)
            ->count();

        return $numberOfDiscounts === 0;
    }

    /**
     * @param string $submittedValue
     * @param \Spryker\Zed\ProductOption\Communication\Form\Constraint\UniqueValue $constraint
     * @param int $idProductOptionValue
     *
     * @return bool
     */
    protected function isValueChanged($submittedValue, UniqueValue $constraint, $idProductOptionValue)
    {
        if (!$idProductOptionValue) {
            return true;
        }

        $productOptionValueEntity = $constraint->getProductOptionQueryContainer()
            ->queryProductOptionByValueId($idProductOptionValue)
            ->findOne();

        if ($productOptionValueEntity->getValue() !== $submittedValue) {
            return true;
        }

        return false;
    }

    /**
     * @param string $optionValue
     *
     * @return string
     */
    protected function addTranslationPrefix($optionValue)
    {
        return ProductOptionConfig::PRODUCT_OPTION_TRANSLATION_PREFIX . $optionValue;
    }

    /**
     * @param string $optionValue
     *
     * @return bool
     */
    protected function hasTranslationPrefix($optionValue)
    {
        return strpos($optionValue, ProductOptionConfig::PRODUCT_OPTION_TRANSLATION_PREFIX) === 0;
    }
}
