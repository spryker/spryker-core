<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Processor\Exception;

use Exception;

class ClassNotFoundException extends Exception
{
    protected const EXCEPTION_MESSAGE_CLASS_NOT_FOUND = 'Class "%s" not found. Have you forgotten to add your custom validator namespace in %s?';
    protected const NAMESPACE_CONFIG_SOURCE = 'Pyz\Glue\RestRequestValidator\RestRequestValidatorConfig::getAvailableConstraintNamespaces()';

    /**
     * @param string $className
     * @param int $code
     */
    public function __construct(string $className, int $code = 0)
    {
        parent::__construct($this->buildMessage($className), $code);
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function buildMessage(string $className)
    {
        $message = sprintf(
            static::EXCEPTION_MESSAGE_CLASS_NOT_FOUND,
            $className,
            static::NAMESPACE_CONFIG_SOURCE
        );

        return $message;
    }
}
