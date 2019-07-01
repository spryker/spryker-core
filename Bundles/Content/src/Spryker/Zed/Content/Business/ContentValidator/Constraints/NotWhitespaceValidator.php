<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Business\ContentValidator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NotWhitespaceValidator extends ConstraintValidator
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
        $trimmedValue = trim($value);

        if (!$constraint instanceof NotWhitespace) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\NotWhitespace');
        }

        if (empty($trimmedValue)) {
            $this->context->buildViolation($constraint->message)
                ->setCode(NotWhitespace::IS_EMPTY_ERROR)
                ->addViolation();
        }
    }
}
