<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class QueryStringValidator extends ConstraintValidator
{
    /**
     * @param string $value
     * @param \Spryker\Zed\Discount\Communication\Form\Constraint\QueryString $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }

        if (!$constraint instanceof QueryString) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\QueryStringConstraint');
        }

        $validationMessages = $this->validateQueryString($value, $constraint);

        if (count($validationMessages) > 0) {
            foreach ($validationMessages as $validationMessage) {
                $this->context
                    ->buildViolation($validationMessage)
                    ->addViolation();
            }
        }
    }

    /**
     * @param string $queryString
     * @param \Spryker\Zed\Discount\Communication\Form\Constraint\QueryString $constraint
     *
     * @return string[]
     */
    protected function validateQueryString($queryString, QueryString $constraint)
    {
        return $constraint->getDiscountFacade()
            ->validateQueryStringByType(
                $constraint->getQueryStringType(),
                $queryString
            );
    }
}
