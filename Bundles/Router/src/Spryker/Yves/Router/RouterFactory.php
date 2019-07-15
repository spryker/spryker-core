<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Router\Loader\ClosureLoader;
use Spryker\Yves\Router\Loader\LoaderInterface;
use Spryker\Yves\Router\Resolver\RequestRequestValueResolver;
use Spryker\Yves\Router\Resource\ResourceInterface;
use Spryker\Yves\Router\Resource\RouterResource;
use Spryker\Yves\Router\Route\RouteCollection;
use Spryker\Yves\Router\Router\ChainRouter;
use Spryker\Yves\Router\Router\Router;
use Spryker\Yves\Router\Router\RouterInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\DefaultValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestAttributeValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\SessionValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\VariadicValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactory;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactoryInterface;

/**
 * @method \Spryker\Yves\Router\RouterConfig getConfig()
 */
class RouterFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Router\Router\ChainRouter */
    public function createRouter()
    {
        return new ChainRouter($this->getRouterPlugins());
    }

    /**
     * @return \Spryker\Yves\RouterExtension\Dependency\Plugin\RouterPluginInterface[]
     */
    public function getRouterPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_PLUGINS);
    }

    /**
     * @return \Spryker\Yves\Router\Router\RouterInterface
     */
    public function createYvesRouter(): RouterInterface
    {
        return new Router(
            $this->createClosureLoader(),
            $this->createResource(),
            $this->getRouterEnhancerPlugins(),
            $this->getConfig()->getRouterConfiguration()
        );
    }

    /**
     * @return \Spryker\Yves\Router\Loader\LoaderInterface
     */
    public function createClosureLoader(): LoaderInterface
    {
        return new ClosureLoader();
    }

    /**
     * @return \Spryker\Yves\Router\Resource\ResourceInterface
     */
    public function createResource(): ResourceInterface
    {
        return new RouterResource(
            $this->createRouteCollection(),
            $this->getRouteProviderPlugins()
        );
    }

    /**
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function createRouteCollection(): RouteCollection
    {
        return new RouteCollection(
            $this->getRouteManipulatorPlugins()
        );
    }

    /**
     * @return \Spryker\Yves\RouterExtension\Dependency\Plugin\PostAddRouteManipulatorPluginInterface[]
     */
    public function getRouteManipulatorPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::POST_ADD_ROUTE_MANIPULATOR);
    }

    /**
     * @return \Spryker\Yves\RouterExtension\Dependency\Plugin\RouteProviderPluginInterface[]
     */
    public function getRouteProviderPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_ROUTE_PROVIDER);
    }

    /**
     * @return \Spryker\Yves\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface[]
     */
    public function getRouterEnhancerPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_ENHANCER_PLUGINS);
    }

    /**
     * @return \Spryker\Yves\Router\Router\RouterInterface
     */
    public function createYvesDevelopmentRouter(): RouterInterface
    {
        return new Router(
            $this->createClosureLoader(),
            $this->createResource(),
            $this->getRouterEnhancerPlugins(),
            $this->getConfig()->getDevelopmentRouterConfiguration()
        );
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface
     */
    public function createArgumentResolver(): ArgumentResolverInterface
    {
        return new ArgumentResolver(
            $this->createArgumentMetaDataFactory(),
            $this->getArgumentValueResolvers()
        );
    }

    /**
     * @return \Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactoryInterface
     */
    public function createArgumentMetaDataFactory(): ArgumentMetadataFactoryInterface
    {
        return new ArgumentMetadataFactory();
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface[]
     */
    public function getArgumentValueResolvers(): array
    {
        return [
            $this->createRequestAttributeValueResolver(),
            $this->createRequestRequestValueResolver(),
            $this->createRequestValueResolver(),
            $this->createSessionValueResolver(),
            $this->createDefaultValueResolver(),
            $this->createVariadicValueResolver(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface
     */
    public function createRequestAttributeValueResolver(): ArgumentValueResolverInterface
    {
        return new RequestAttributeValueResolver();
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface
     */
    public function createRequestRequestValueResolver(): ArgumentValueResolverInterface
    {
        return new RequestRequestValueResolver();
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface
     */
    public function createRequestValueResolver(): ArgumentValueResolverInterface
    {
        return new RequestValueResolver();
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface
     */
    public function createSessionValueResolver(): ArgumentValueResolverInterface
    {
        return new SessionValueResolver();
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface
     */
    public function createDefaultValueResolver(): ArgumentValueResolverInterface
    {
        return new DefaultValueResolver();
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface
     */
    public function createVariadicValueResolver(): ArgumentValueResolverInterface
    {
        return new VariadicValueResolver();
    }
}
