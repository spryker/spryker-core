<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint;

use Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig;

class RestRequestValidatorConstraintResolver implements RestRequestValidatorConstraintResolverInterface
{
    /**
     * @var \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig $config
     */
    public function __construct(RestRequestValidatorConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $className
     *
     * @return null|string
     */
    public function resolveConstraintClassName(string $className): ?string
    {
        foreach ($this->config->getAvailableConstraintNamespaces() as $constraintNamespace) {
            if (class_exists($constraintNamespace . $className)) {
                return $constraintNamespace . $className;
            }
        }

        return null;
    }
}
