<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Constraints\Optional;

use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContext;

class PaymentsValidator
{
    protected const SYMFONY_COMPONENT_VALIDATOR_CONSTRAINTS_NAMESPACE = '\\Symfony\\Component\\Validator\\Constraints\\';

    protected const PAYMENT_CONSTRAINTS = [
        'paymentMethod' => [
            'NotBlank',
        ],
        'paymentProvider' => [
            'NotBlank',
        ],
        'paymentSelection' => [
            'NotBlank',
        ],
    ];

    protected const CONSTRAINT_PARAMETERS = [
        'allowExtraFields' => true,
        'groups' => ['Default'],
    ];

    protected const CONSTRAINTS_CONSTRAINT_PARAMETERS = 'constraints';
    protected const FIELDS_CONSTRAINT_PARAMETERS = 'fields';

    /**
     * @param string[] $value
     * @param \Symfony\Component\Validator\Context\ExecutionContext $context
     *
     * @return void
     */
    public function validate($value, ExecutionContext $context): void
    {
        if (count(array_filter($value)) === 0) {
            return;
        }

        $violationList = static::getViolationsList($value, $context);

        foreach ($violationList as $violation) {
            $context
                ->buildViolation($violation->getMessage())
                ->atPath($violation->getPropertyPath())
                ->addViolation();
        }
    }

    /**
     * @return array
     */
    protected static function getNestedConstraints(): array
    {
        $constraintConfig = [];

        foreach (static::PAYMENT_CONSTRAINTS as $field => $constraints) {
            foreach ($constraints as $constraint => $parameters) {
                if (!$constraint) {
                    $constraint = $parameters;
                    $parameters = null;
                }

                $className = static::SYMFONY_COMPONENT_VALIDATOR_CONSTRAINTS_NAMESPACE . $constraint;
                $constraintConfig[$field][] = new $className($parameters);
            }
        }

        return $constraintConfig;
    }

    /**
     * @param string[] $value
     * @param \Symfony\Component\Validator\Context\ExecutionContext $context
     *
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    protected static function getViolationsList(array $value, ExecutionContext $context): ConstraintViolationListInterface
    {
        $collectionConstraintOptions = [static::FIELDS_CONSTRAINT_PARAMETERS => static::getNestedConstraints()] + static::CONSTRAINT_PARAMETERS;
        $collectionConstraint = new Collection($collectionConstraintOptions);
        $constraintOptions = [static::CONSTRAINTS_CONSTRAINT_PARAMETERS => $collectionConstraint];
        $constraintsCollection = new All($constraintOptions);

        return $context->getValidator()->validate($value, $constraintsCollection);
    }
}
