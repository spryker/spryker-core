<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
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
     * @param mixed $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (in_array($value, $this->validatedValues)) {
            $this->buildViolation('Product option with this value is already used.')
                ->addViolation();
        }

        if (!$constraint instanceof UniqueValue) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\UniqueValue');
        }

        if (!$this->hasTranslationPrefix($value)) {
            $value = $this->addTranslationPrefix($value);
        }

        if (!$this->isValueChanged($value, $constraint)) {
            return;
        }

        if (!$this->isUniqueValue($value, $constraint)) {
            $this->buildViolation('Product option with this sku is already used.')
                ->addViolation();
        }

        $this->validatedValues[] = $value;
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
            ->queryProductOptionValue(ProductOptionConfig::PRODUCT_OPTION_TRANSLATION_PREFIX . $value)
            ->count();

        return $numberOfDiscounts === 0;
    }

    /**
     * @param string $submittedValue
     * @param \Spryker\Zed\ProductOption\Communication\Form\Constraint\UniqueValue $constraint
     *
     * @return bool
     */
    protected function isValueChanged($submittedValue, UniqueValue $constraint)
    {
        /** @var \Symfony\Component\Form\Form $root */
        $root = $this->context->getRoot();

        $idProductOptionValue = $this->findProductOptionValueId($root->getData(), $submittedValue);
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
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param string $submittedValue
     *
     * @return int|null
     */
    protected function findProductOptionValueId(ProductOptionGroupTransfer $productOptionGroupTransfer, $submittedValue)
    {
        foreach ($productOptionGroupTransfer->getProductOptionValues() as $productOptionValueTransfer) {
            if ($productOptionValueTransfer->getValue() === $submittedValue) {
                return $productOptionValueTransfer->getIdProductOptionValue();
            }
        }

        return null;
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
