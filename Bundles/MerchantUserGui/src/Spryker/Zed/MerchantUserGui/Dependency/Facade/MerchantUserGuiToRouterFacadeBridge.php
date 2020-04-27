<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Dependency\Facade;

use Spryker\Zed\Router\Business\Router\ChainRouter;

class MerchantUserGuiToRouterFacadeBridge implements MerchantUserGuiToRouterFacadeInterface
{
    /**
     * @var \Spryker\Zed\Router\Business\RouterFacadeInterface
     */
    protected $routerFacade;

    /**
     * @param \Spryker\Zed\Router\Business\RouterFacadeInterface $routerFacade
     */
    public function __construct($routerFacade)
    {
        $this->routerFacade = $routerFacade;
    }

    /**
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function getRouter(): ChainRouter
    {
        return $this->routerFacade->getRouter();
    }
}
