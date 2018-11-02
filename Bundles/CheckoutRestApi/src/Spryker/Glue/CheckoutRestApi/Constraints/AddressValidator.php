<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Constraints;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContext;

class AddressValidator
{
    protected const UUID = 'uuid';
    protected const SYMFONY_COMPONENT_VALIDATOR_CONSTRAINTS_NAMESPACE = '\\Symfony\\Component\\Validator\\Constraints\\';
    protected const ADDRESS_CONSTRAINS = [
        'salutation' => [
            'NotBlank',
            'Choice' => [
                'choices' =>
                    [
                        'Mr',
                        'Mrs',
                        'Ms',
                        'Dr',
                    ],
                ],
            ],
        'firstName' => [
            'NotBlank',
        ],
        'lastName' => [
            'NotBlank',
        ],
        'address1' => [
            'NotBlank',
        ],
        'address2' => [
            'NotBlank',
        ],
        'zipCode' => [
            'NotBlank',
        ],
        'city' => [
            'NotBlank',
        ],
        'iso2Code' => [
            'NotBlank',
        ],
    ];
    protected const CONSTRAINT_PARAMETERS = [
        'allowExtraFields' => true,
        'groups' => ['Default'],
    ];
    protected const FIELDS = 'fields';

    /**
     * @param string $value
     * @param \Symfony\Component\Validator\Context\ExecutionContext $context
     *
     * @return void
     */
    public function validate($value, ExecutionContext $context): void
    {
        $violationList = [];
        if (isset($value[static::UUID])) {
            $constraint = new NotBlank();
            $violationList = $context->getValidator()->validate($value[static::UUID], $constraint);
        }

        if (!isset($value[static::UUID])) {
            $constraintsCollection = new Collection([static::FIELDS => static::getNestedConstraints()] + static::CONSTRAINT_PARAMETERS);
            $violationList = $context->getValidator()->validate($value, $constraintsCollection);
        }

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

        foreach (static::ADDRESS_CONSTRAINS as $field => $constraints) {
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
}
