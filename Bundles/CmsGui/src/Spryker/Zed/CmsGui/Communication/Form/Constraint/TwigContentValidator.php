<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Twig\Error\SyntaxError;

class TwigContentValidator extends ConstraintValidator
{
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
        if (!$constraint instanceof TwigContent) {
            throw new UnexpectedTypeException($constraint, TwigContent::class);
        }

        if (!$this->isTwigContent($value)) {
            return;
        }

        try {
            $this->validateTwigContent($value, $constraint);
        } catch (SyntaxError $exception) {
            $this->context->buildViolation(
                sprintf(
                    'Invalid twig template: %s',
                    $exception->getMessage()
                )
            )->addViolation();
        }
    }

    /**
     * @param string $value
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\TwigContent $constraint
     *
     * @return void
     */
    protected function validateTwigContent($value, TwigContent $constraint)
    {
        $twigEnvironment = $constraint->getTwigEnvironment();
        $twigEnvironment->tokenize(str_replace(['</p>', '<br>'], PHP_EOL, $value));
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    protected function isTwigContent($value)
    {
        return strpos($value, '{{') !== false && strpos($value, '}}') !== false;
    }
}
