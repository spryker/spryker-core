<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Dependency\Facade;

use Spryker\Zed\Router\Business\Router\RouterInterface;

class ZedNavigationToRouterFacadeBridge implements ZedNavigationToRouterFacadeInterface
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
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function getBackofficeRouter(): RouterInterface
    {
        return $this->routerFacade->getBackofficeRouter();
    }
}
