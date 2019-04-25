<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StorageRouter\RouteEnhancer;

use Spryker\Yves\Kernel\BundleControllerAction;
use Spryker\Yves\Kernel\ClassResolver\Controller\ControllerResolver;
use Spryker\Yves\Kernel\Controller\BundleControllerActionRouteNameResolver;
use Spryker\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface;
use Symfony\Cmf\Component\Routing\Enhancer\RouteEnhancerInterface;
use Symfony\Component\HttpFoundation\Request;

class ControllerRouteEnhancer implements RouteEnhancerInterface
{
    /**
     * @var \Spryker\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface[]
     */
    protected $resourceCreatorPlugins;

    /**
     * @param \Spryker\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface[] $resourceCreatorPlugins
     */
    public function __construct(array $resourceCreatorPlugins)
    {
        $this->resourceCreatorPlugins = $resourceCreatorPlugins;
    }

    /**
     * @param array $defaults
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function enhance(array $defaults, Request $request)
    {
        foreach ($this->resourceCreatorPlugins as $resourceCreator) {
            if ($defaults['type'] === $resourceCreator->getType()) {
                return $this->createResource($resourceCreator, $defaults['data']);
            }
        }

        return $defaults;
    }

    /**
     * @param \Spryker\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface $resourceCreator
     * @param array $data
     *
     * @return array
     */
    protected function createResource(ResourceCreatorPluginInterface $resourceCreator, array $data)
    {
        $bundleControllerAction = new BundleControllerAction($resourceCreator->getModuleName(), $resourceCreator->getControllerName(), $resourceCreator->getActionName());
        $routeResolver = new BundleControllerActionRouteNameResolver($bundleControllerAction);

        $controllerResolver = new ControllerResolver();
        $controller = $controllerResolver->resolve($bundleControllerAction);
        $actionName = $resourceCreator->getActionName();
        if (strrpos($actionName, 'Action') === false) {
            $actionName .= 'Action';
        }

        $resourceCreatorResult = $resourceCreator->mergeResourceData($data);
        $resourceCreatorResult['_controller'] = [$controller, $actionName];
        $resourceCreatorResult['_route'] = $routeResolver->resolve();

        return $resourceCreatorResult;
    }
}
