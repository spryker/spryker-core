<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CategoryNameExistsValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint|CategoryNameExists $constraint The constraint for the validation
     *
     * @api
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        $idLocale = $constraint->getLocale()->getIdLocale();
        $idCategory = $constraint->getIdCategory();
        $categoryQueryContainer = $constraint->getQueryContainer();
        $categoryEntity = $categoryQueryContainer
            ->queryCategory($value, $idLocale)
            ->findOne();

        if (!is_null($categoryEntity)) {
            if (is_null($idCategory)
                || $idCategory !== $categoryEntity->getIdCategory()) {
                $this->addViolation($value, $constraint);
            }
        }
    }

    /**
     * @param string $value
     * @param Constraint|CategoryNameExists $constraint
     *
     * @return void
     */
    protected function addViolation($value, Constraint $constraint)
    {
        $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }

}
