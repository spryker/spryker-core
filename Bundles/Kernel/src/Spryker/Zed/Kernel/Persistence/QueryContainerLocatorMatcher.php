<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence;

use Spryker\Shared\Kernel\Locator\LocatorMatcherInterface;

class QueryContainerLocatorMatcher implements LocatorMatcherInterface
{
    public const METHOD_PREFIX = 'queryContainer';

    /**
     * @api
     *
     * @param string $method
     *
     * @return bool
     */
    public function match($method)
    {
        return (strpos($method, self::METHOD_PREFIX) === 0);
    }
}
