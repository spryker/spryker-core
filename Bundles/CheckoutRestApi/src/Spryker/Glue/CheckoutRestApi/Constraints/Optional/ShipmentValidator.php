<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Constraints\Optional;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContext;

class ShipmentValidator
{
    protected const SYMFONY_COMPONENT_VALIDATOR_CONSTRAINTS_NAMESPACE = '\\Symfony\\Component\\Validator\\Constraints\\';

    protected const SHIPMENT_CONSTRAINTS = [
        'shipmentSelection' => [
            'NotBlank',
        ],
        'method' => [
            [
                'Collection' => [
                    'fields' => [
                        'carrierName' => ['NotBlank'],
                        'id' => ['NotBlank', 'Type' => ['type' => 'numeric']],
                        'name' => ['NotBlank'],
                        'price' => ['NotBlank', 'Type' => ['type' => 'numeric']],
                    ],
                ],
            ],
        ],
    ];

    protected const CONSTRAINT_PARAMETERS = [
        'allowExtraFields' => true,
        'groups' => ['Default'],
    ];

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
     * @param array $constraintList
     *
     * @return array
     */
    protected static function getNestedConstraints(array $constraintList): array
    {
        $constraintConfig = [];

        foreach ($constraintList as $field => $constraints) {
            foreach ($constraints as $constraint => $parameters) {
                if (!$constraint && is_array($parameters)) {
                    $constraint = key($parameters);
                    $parameters = [static::FIELDS_CONSTRAINT_PARAMETERS => static::getNestedConstraints(reset($parameters)[static::FIELDS_CONSTRAINT_PARAMETERS])] + static::CONSTRAINT_PARAMETERS;
                }

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
        $collectionConstraintOptions = [static::FIELDS_CONSTRAINT_PARAMETERS => static::getNestedConstraints(static::SHIPMENT_CONSTRAINTS)] + static::CONSTRAINT_PARAMETERS;
        $collectionConstraint = new Collection($collectionConstraintOptions);

        return $context->getValidator()->validate($value, $collectionConstraint);
    }
}
