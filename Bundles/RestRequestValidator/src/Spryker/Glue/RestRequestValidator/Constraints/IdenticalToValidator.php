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
    protected const INVALID_PROPERTY_PATH = 'Invalid property path "%s" provided to "%s" constraint.';

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
        if (!$constraint instanceof IdenticalTo) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\IdenticalTo');
        }

        if ($value === null) {
            return;
        }

        $comparedValue = $this->getComparedValue($value, $constraint);

        if (!$this->compareValues($value, $comparedValue)) {
            $this->buildViolation($value, $constraint);
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

    /**
     * @param mixed $value
     * @param string $comparedValue
     *
     * @return \DateTime|\DateTimeImmutable|string
     */
    protected function convertToDateTime($value, string $comparedValue)
    {
        if (is_string($comparedValue)) {
            if ($value instanceof DateTimeImmutable) {
                $comparedValue = new DateTimeImmutable($comparedValue);
            } elseif ($value instanceof DateTimeInterface) {
                $comparedValue = new DateTime($comparedValue);
            }
        }
        return $comparedValue;
    }

    /**
     * @param mixed $value
     * @param \Spryker\Glue\RestRequestValidator\Constraints\IdenticalTo $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     *
     * @return \DateTime|\DateTimeImmutable|string
     */
    protected function getComparedValue($value, IdenticalTo $constraint)
    {
        if ($path = $constraint->propertyPath) {
            if (!isset($this->context->getRoot()[$path])) {
                throw new ConstraintDefinitionException(sprintf(static::INVALID_PROPERTY_PATH, $path, get_class($constraint)));
            }
            $comparedValue = $this->context->getRoot()[$path];
        } else {
            $comparedValue = $constraint->value;
        }

        $comparedValue = $this->convertToDateTime($value, $comparedValue);

        return $comparedValue;
    }

    /**
     * @param mixed $value
     * @param \Spryker\Glue\RestRequestValidator\Constraints\IdenticalTo $constraint
     *
     * @return void
     */
    protected function buildViolation($value, IdenticalTo $constraint): void
    {
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $this->formatValue($value, self::OBJECT_TO_STRING | self::PRETTY_DATE))
            ->setParameter('{{ property_path }}', $constraint->propertyPath)
            ->setCode($this->getErrorCode())
            ->addViolation();
    }
}
