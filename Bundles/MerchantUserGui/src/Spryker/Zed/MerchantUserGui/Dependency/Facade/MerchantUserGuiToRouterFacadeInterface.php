<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Dependency\Facade;

use Spryker\Zed\Router\Business\Router\ChainRouter;

interface MerchantUserGuiToRouterFacadeInterface
{
    /**
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function getRouter(): ChainRouter;
}
