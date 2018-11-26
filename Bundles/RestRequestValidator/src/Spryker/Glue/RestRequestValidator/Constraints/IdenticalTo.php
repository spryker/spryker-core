<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class IdenticalTo extends Constraint
{
    /**
     * @var string
     */
    public $value;

    /**
     * @var string
     */
    public $propertyPath;

    /**
     * @var string
     */
    public $message = 'This value should be identical to {{ compared_value_type }} {{ compared_value }}.';

    public const NOT_IDENTICAL_ERROR = '2a8cc50f-58a2-4536-875e-060a2ce69ed5';

    protected static $errorNames = [
        self::NOT_IDENTICAL_ERROR => 'NOT_IDENTICAL_ERROR',
    ];

    /**
     * @param array|null $options
     *
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function __construct($options = null)
    {
        if ($options === null) {
            $options = [];
        }

        if (is_array($options)) {
            $valueOptionSet = isset($options['value']);
            $propertyPathOptionSet = isset($options['propertyPath']);
            if (!$valueOptionSet && !$propertyPathOptionSet) {
                throw new ConstraintDefinitionException(sprintf('The "%s" constraint requires either the "value" or "propertyPath" option to be set.', get_class($this)));
            }

            if ($valueOptionSet && $propertyPathOptionSet) {
                throw new ConstraintDefinitionException(sprintf('The "%s" constraint requires only one of the "value" or "propertyPath" options to be set, not both.', get_class($this)));
            }
        }

        parent::__construct($options);
    }
}
