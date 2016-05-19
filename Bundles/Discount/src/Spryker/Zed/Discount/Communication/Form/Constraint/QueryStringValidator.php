<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\ConstraintValidator;

class QueryStringValidator extends ConstraintValidator
{
    /**
     * @param string $value
     * @param \Symfony\Component\Validator\Constraint|QueryString $constraint
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
                $this->buildViolation($validationMessage)
                    ->addViolation();
            }
        }
    }

    /**
     * @param string $queryString
     * @param QueryString|\Spryker\Zed\User\Communication\Form\Constraints\CurrentPassword $constraint
     *
     * @return array|string[]
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
