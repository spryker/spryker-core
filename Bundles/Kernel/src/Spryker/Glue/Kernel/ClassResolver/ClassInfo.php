<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\ClassResolver;

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
