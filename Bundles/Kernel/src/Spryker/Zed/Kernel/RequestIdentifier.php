<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel;

class RequestIdentifier
{
    /**
     * @var string
     */
    protected static $requestId;

    /**
     * @return string
     */
    public static function getRequestId()
    {
        if (static::$requestId === null) {
            static::$requestId = uniqid(static::getPrefix(), true);
        }

        return static::$requestId;
    }

    /**
     * @return string
     */
    protected static function getPrefix()
    {
        $resource = PHP_SAPI;
        $hostName = (gethostname()) ?: php_uname('n');

        return sprintf('%s-%s-%d', $resource, $hostName, posix_getpid());
    }
}
