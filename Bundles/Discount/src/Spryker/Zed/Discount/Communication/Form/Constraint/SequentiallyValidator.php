<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * The class exists for BC reasons only.
 * Symfony\Component\Validator\Constraints\SequentiallyValidator is not supported in Symfony 4.
 */
class SequentiallyValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Sequentially) {
            throw new UnexpectedTypeException($constraint, Sequentially::class);
        }

        $context = $this->context;
        $validator = $context->getValidator()->inContext($context);
        $originalCount = $validator->getViolations()->count();

        foreach ($constraint->constraints as $subСonstraint) {
            if ($originalCount !== $validator->validate($value, $subСonstraint)->getViolations()->count()) {
                break;
            }
        }
    }
}
