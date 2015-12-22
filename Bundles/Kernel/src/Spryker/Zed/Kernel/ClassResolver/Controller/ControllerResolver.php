<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\ClassResolver\Controller;

use Spryker\Shared\Kernel\ClassResolver\Controller\AbstractControllerResolver;

class ControllerResolver extends AbstractControllerResolver
{

    const CLASS_NAME_PATTERN = '\\%s\\Zed\\%s%s\\Communication\\Controller\\%sController';

    /**
     * @return string
     */
    protected function getClassNamePattern()
    {
        return self::CLASS_NAME_PATTERN;
    }

}
