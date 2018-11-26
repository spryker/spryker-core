<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Constraints;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\AbstractComparisonValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IdenticalToValidator extends AbstractComparisonValidator
{
    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof IdenticalTo) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\IdenticalTo');
        }

        if ($value === null) {
            return;
        }

        if ($path = $constraint->propertyPath) {
            if (!isset($this->context->getRoot()[$path])) {
                throw new ConstraintDefinitionException(sprintf('Invalid property path "%s" provided to "%s" constraint.', $path, get_class($constraint)));
            }
            $comparedValue = $this->context->getRoot()[$path];
        } else {
            $comparedValue = $constraint->value;
        }

        // Convert strings to DateTimes if comparing another DateTime
        // This allows to compare with any date/time value supported by
        // the DateTime constructor:
        // http://php.net/manual/en/datetime.formats.php
        if (is_string($comparedValue)) {
            if ($value instanceof DateTimeImmutable) {
                // If $value is immutable, convert the compared value to a
                // DateTimeImmutable too
                $comparedValue = new DateTimeImmutable($comparedValue);
            } elseif ($value instanceof DateTimeInterface) {
                // Otherwise use DateTime
                $comparedValue = new DateTime($comparedValue);
            }
        }

        if (!$this->compareValues($value, $comparedValue)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value, self::OBJECT_TO_STRING | self::PRETTY_DATE))
                ->setParameter('{{ compared_value }}', $this->formatValue($comparedValue, self::OBJECT_TO_STRING | self::PRETTY_DATE))
                ->setParameter('{{ compared_value_type }}', $this->formatTypeOf($comparedValue))
                ->setCode($this->getErrorCode())
                ->addViolation();
        }
    }

    /**
     * @param mixed $value1
     * @param mixed $value2
     *
     * @return bool
     */
    protected function compareValues($value1, $value2)
    {
        return $value1 === $value2;
    }

    /**
     * @return string|null
     */
    protected function getErrorCode()
    {
        return IdenticalTo::NOT_IDENTICAL_ERROR;
    }
}
