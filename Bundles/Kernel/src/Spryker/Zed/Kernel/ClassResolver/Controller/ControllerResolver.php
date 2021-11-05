<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\Controller;

use Spryker\Shared\Kernel\ClassResolver\Controller\AbstractControllerResolver;

class ControllerResolver extends AbstractControllerResolver
{
    /**
     * @var string
     */
    public const CLASS_NAME_PATTERN = '\\%s\\Zed\\%s%s\\Communication\\Controller\\%sController';

    /**
     * @return string
     */
    protected function getClassNamePattern()
    {
        return static::CLASS_NAME_PATTERN;
    }
}
