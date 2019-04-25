<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StorageRouter;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientInterface;
use Spryker\Yves\StorageRouter\ParameterMerger\ParameterMerger;
use Spryker\Yves\StorageRouter\ParameterMerger\ParameterMergerInterface;
use Spryker\Yves\StorageRouter\RequestMatcher\StorageRequestMatcher;
use Spryker\Yves\StorageRouter\RouteEnhancer\ControllerRouteEnhancer;
use Spryker\Yves\StorageRouter\Router\DynamicRouter;
use Spryker\Yves\StorageRouter\UrlGenerator\StorageUrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method \Spryker\Yves\StorageRouter\StorageRouterConfig getConfig()
 */
class StorageRouterFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Routing\RouterInterface
     */
    public function createRouter(): RouterInterface
    {
        return new DynamicRouter(
            $this->createRequestMatcher(),
            $this->createUrlGenerator(),
            $this->createRouteEnhancer()
        );
    }

    /**
     * @return \Symfony\Cmf\Component\Routing\Enhancer\RouteEnhancerInterface[]
     */
    public function createRouteEnhancer(): array
    {
        return [
            new ControllerRouteEnhancer($this->getResourceCreatorPlugins()),
        ];
    }

    /**
     * @return \Symfony\Component\Routing\Matcher\RequestMatcherInterface
     */
    public function createRequestMatcher(): RequestMatcherInterface
    {
        return new StorageRequestMatcher(
            $this->getUrlStorageClient()
        );
    }

    /**
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    public function createUrlGenerator(): UrlGeneratorInterface
    {
        return new StorageUrlGenerator(
            $this->getUrlStorageClient(),
            $this->createParameterMerger()
        );
    }

    /**
     * @return \Spryker\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientInterface
     */
    public function getUrlStorageClient(): StorageRouterToUrlStorageClientInterface
    {
        return $this->getProvidedDependency(StorageRouterDependencyProvider::CLIENT_URL_STORAGE);
    }

    /**
     * @return \Spryker\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface[]
     */
    public function getResourceCreatorPlugins(): array
    {
        return $this->getProvidedDependency(StorageRouterDependencyProvider::PLUGIN_RESOURCE_CREATORS);
    }

    /**
     * @return \Spryker\Yves\StorageRouter\ParameterMerger\ParameterMergerInterface
     */
    protected function createParameterMerger(): ParameterMergerInterface
    {
        return new ParameterMerger();
    }
}
