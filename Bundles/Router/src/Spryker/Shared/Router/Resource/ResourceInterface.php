<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Router\Resource;

use Spryker\Shared\Router\Route\RouteCollection;

interface ResourceInterface
{
    /**
     * @return RouteCollection
     */
    public function __invoke(): RouteCollection;
}
