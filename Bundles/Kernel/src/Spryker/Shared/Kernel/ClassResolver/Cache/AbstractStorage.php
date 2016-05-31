<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Cache;

abstract class AbstractStorage
{

    /**
     * @var bool
     */
    protected static $modified = false;

    /**
     * @return bool
     */
    protected function isModified()
    {
        return self::$modified;
    }

    protected function markAsModified()
    {
        self::$modified = true;
    }

}
