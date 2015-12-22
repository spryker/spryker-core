<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel\ClassResolver\Controller;

use Spryker\Shared\Kernel\ClassResolver\Controller\AbstractControllerResolver;

class ControllerResolver extends AbstractControllerResolver
{

    const CLASS_NAME_PATTERN = '\\%s\\Yves\\%s%s\\Controller\\%sController';

    /**
     * @return string
     */
    protected function getClassNamePattern()
    {
        return self::CLASS_NAME_PATTERN;
    }

}
