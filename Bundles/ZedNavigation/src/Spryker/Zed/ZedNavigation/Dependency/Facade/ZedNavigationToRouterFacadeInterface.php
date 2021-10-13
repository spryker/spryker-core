<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Dependency\Facade;

use Spryker\Zed\Router\Business\Router\RouterInterface;

interface ZedNavigationToRouterFacadeInterface
{
    /**
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function getBackofficeRouter(): RouterInterface;
}
