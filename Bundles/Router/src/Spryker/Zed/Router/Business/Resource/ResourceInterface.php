<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Resource;

use Spryker\Zed\Router\Business\Route\RouteCollection;

interface ResourceInterface
{
    /**
     * @return \Spryker\Zed\Router\Business\Route\RouteCollection
     */
    public function __invoke(): RouteCollection;
}
