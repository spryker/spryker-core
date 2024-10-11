<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Dependency\Facade;

use Generated\Shared\Transfer\RouterActionCollectionTransfer;
use Generated\Shared\Transfer\RouterBundleCollectionTransfer;
use Generated\Shared\Transfer\RouterControllerCollectionTransfer;

class AclToRouterFacadeBridge implements AclToRouterFacadeInterface
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
     * @return \Generated\Shared\Transfer\RouterBundleCollectionTransfer
     */
    public function getRouterBundleCollection(): RouterBundleCollectionTransfer
    {
        return $this->routerFacade->getRouterBundleCollection();
    }

    /**
     * @param string $bundle
     *
     * @return \Generated\Shared\Transfer\RouterControllerCollectionTransfer
     */
    public function getRouterControllerCollection(string $bundle): RouterControllerCollectionTransfer
    {
        return $this->routerFacade->getRouterControllerCollection($bundle);
    }

    /**
     * @param string $bundle
     * @param string $controller
     *
     * @return \Generated\Shared\Transfer\RouterActionCollectionTransfer
     */
    public function getRouterActionCollection(string $bundle, string $controller): RouterActionCollectionTransfer
    {
        return $this->routerFacade->getRouterActionCollection($bundle, $controller);
    }
}
