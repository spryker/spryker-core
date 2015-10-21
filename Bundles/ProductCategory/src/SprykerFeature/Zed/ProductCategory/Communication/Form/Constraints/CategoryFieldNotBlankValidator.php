<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\ProductCategory\Communication\Form\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlankValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CategoryFieldNotBlankValidator extends NotBlankValidator
{

    /**
     * @param string $value
     * @param Constraint $constraint
     *
     * @throws UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!($constraint instanceof CategoryFieldNotBlank)) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Category');
        }

        if ($this->shouldValidateCategory($constraint)) {
            parent::validate($value, $constraint);
        }
    }

    /**
     * @param CategoryFieldNotBlank $constraint
     *
     * @return bool
     */
    protected function shouldValidateCategory(CategoryFieldNotBlank $constraint)
    {
        $isNotCategorySelected = $this->context->getRoot()
                ->get($constraint->getCategoryFieldName())
                ->getData() === null;

        $isNotSubcategoryChecked = !$this->isSubcategoriesChecked($constraint->getCheckboxFieldName());

        return $isNotSubcategoryChecked && $isNotCategorySelected;
    }

    /**
     * @param $checkboxName
     *
     * @return bool
     */
    protected function isSubcategoriesChecked($checkboxName)
    {
        return (bool) $this->context->getRoot()->get($checkboxName)->getData();
    }

}
