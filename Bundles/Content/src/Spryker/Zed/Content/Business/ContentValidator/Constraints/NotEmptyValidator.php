<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Business\ContentValidator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NotEmptyValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        $value = trim($value);

        if (!$constraint instanceof NotEmpty) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\NotEmpty');
        }

        if (empty($value) && $value != '0') {
            $this->context->buildViolation($constraint->message)
                ->setCode(NotEmpty::IS_EMPTY_ERROR)
                ->addViolation();
        }
    }
}
