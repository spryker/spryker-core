<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Reader;

use Generated\Shared\Transfer\RouterActionCollectionTransfer;
use Generated\Shared\Transfer\RouterBundleCollectionTransfer;
use Generated\Shared\Transfer\RouterControllerCollectionTransfer;
use Symfony\Component\Routing\RouteCollection;

class RouterReader implements RouterReaderInterface
{
    /**
     * @var \Symfony\Component\Routing\RouteCollection
     */
    protected RouteCollection $routeCollection;

    /**
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     */
    public function __construct(RouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    /**
     * @return \Generated\Shared\Transfer\RouterBundleCollectionTransfer
     */
    public function getBundleCollection(): RouterBundleCollectionTransfer
    {
        $tree = $this->tree();
        $bundles = array_keys($tree);

        return (new RouterBundleCollectionTransfer())->setBundles($bundles);
    }

    /**
     * @param string $bundle
     *
     * @return \Generated\Shared\Transfer\RouterControllerCollectionTransfer
     */
    public function getControllerCollection(string $bundle): RouterControllerCollectionTransfer
    {
        $routerControllerCollectionTransfer = (new RouterControllerCollectionTransfer());

        $tree = $this->tree();
        if (!isset($tree[$bundle])) {
            return $routerControllerCollectionTransfer;
        }
        /** @var array<string> $controllers */
        $controllers = array_keys($tree[$bundle]);

        return $routerControllerCollectionTransfer->setControllers($controllers);
    }

    /**
     * @param string $bundle
     * @param string $controller
     *
     * @return \Generated\Shared\Transfer\RouterActionCollectionTransfer
     */
    public function getActionCollection(string $bundle, string $controller): RouterActionCollectionTransfer
    {
        $routerActionCollectionTransfer = (new RouterActionCollectionTransfer());

        $tree = $this->tree();
        if (!isset($tree[$bundle][$controller])) {
            return $routerActionCollectionTransfer;
        }
        $actions = $tree[$bundle][$controller];

        return $routerActionCollectionTransfer->setActions($actions);
    }

    /**
     * @return array<string, mixed>
     */
    protected function tree(): array
    {
        $tree = [];

        foreach ($this->routeCollection->all() as $key => $route) {
            $parts = explode(':', $key);

            if (!isset($tree[$parts[0]])) {
                $tree[$parts[0]] = [];
            }

            if (isset($parts[1]) && !isset($tree[$parts[0]][$parts[1]])) {
                $tree[$parts[0]][$parts[1]] = [];
            }

            if (isset($parts[2])) {
                $tree[$parts[0]][$parts[1]][] = $parts[2];
            }
        }

        return $tree;
    }
}
