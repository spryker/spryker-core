<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\ClassResolver;

use Spryker\Shared\Kernel\ClassResolver\ClassInfo as SharedClassInfo;

class ClassInfo extends SharedClassInfo
{

    const KEY_LAYER = 3;

    /**
     * @return string
     */
    public function getLayer()
    {
        return $this->callerClassParts[self::KEY_LAYER];
    }

}
