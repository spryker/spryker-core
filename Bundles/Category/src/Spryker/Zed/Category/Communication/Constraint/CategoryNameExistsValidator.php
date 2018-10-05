<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @deprecated Will be removed with next major release
 */
class CategoryNameExistsValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @api
     *
     * @param mixed $value The value that should be validated
     * @param \Spryker\Zed\Category\Communication\Constraint\CategoryNameExists $constraint The constraint for the validation
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

        if ($categoryEntity !== null) {
            if ($idCategory === null
                || $idCategory !== $categoryEntity->getIdCategory()) {
                $this->addViolation($value, $constraint);
            }
        }
    }

    /**
     * @param string $value
     * @param \Spryker\Zed\Category\Communication\Constraint\CategoryNameExists $constraint
     *
     * @return void
     */
    protected function addViolation($value, Constraint $constraint)
    {
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
