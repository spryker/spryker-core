<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Processor\Exception;

use Exception;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;

class ConstraintNotFoundException extends Exception
{
    protected const EXCEPTION_MESSAGE_CLASS_NOT_FOUND = 'Class "%s" not found. Have you forgotten to add your custom validator namespace in %s?';
    protected const NAMESPACE_CONFIG_SOURCE = '%s\%s\%s\%s::%s()';

    /**
     * @param string $constraintName
     * @param int $code
     */
    public function __construct(string $constraintName, int $code = 0)
    {
        parent::__construct($this->buildMessage($constraintName), $code);
    }

    /**
     * @param string $constraintName
     *
     * @return string
     */
    protected function buildMessage(string $constraintName)
    {
        $configNamespace = sprintf(
            static::NAMESPACE_CONFIG_SOURCE,
            Config::getInstance()->get(KernelConstants::PROJECT_NAMESPACE),
            'Glue',
            'RestRequestValidator',
            'RestRequestValidatorConfig',
            'getAvailableConstraintNamespaces'
        );

        $message = sprintf(
            static::EXCEPTION_MESSAGE_CLASS_NOT_FOUND,
            $constraintName,
            $configNamespace
        );

        return $message;
    }
}
