<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Router;

use Spryker\Shared\RouterExtension\Dependency\Plugin\RouterEnhancerAwareInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\Router as SymfonyRouter;

class Router extends SymfonyRouter implements RouterInterface
{
    /**
     * @var \Spryker\Shared\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface[]
     */
    protected $routerEnhancerPlugins;

    /**
     * @param \Symfony\Component\Config\Loader\LoaderInterface $loader
     * @param mixed $resource
     * @param \Spryker\Shared\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface[] $routerEnhancerPlugins
     * @param array $options
     */
    public function __construct(LoaderInterface $loader, $resource, array $routerEnhancerPlugins, array $options = [])
    {
        parent::__construct($loader, $resource, $options);

        $this->routerEnhancerPlugins = $routerEnhancerPlugins;
    }

    /**
     * @return \Spryker\Shared\RouterExtension\Dependency\Plugin\RouterEnhancerAwareInterface|\Symfony\Component\Routing\Matcher\UrlMatcherInterface|null
     */
    public function getMatcher()
    {
        $this->matcher = parent::getMatcher();

        if ($this->matcher instanceof RouterEnhancerAwareInterface) {
            $this->matcher->setRouterEnhancerPlugins($this->routerEnhancerPlugins);
        }

        return $this->matcher;
    }

    /**
     * @return \Spryker\Shared\RouterExtension\Dependency\Plugin\RouterEnhancerAwareInterface|\Symfony\Component\Routing\Generator\UrlGeneratorInterface|null
     */
    public function getGenerator()
    {
        $this->generator = parent::getGenerator();

        if ($this->generator instanceof RouterEnhancerAwareInterface) {
            $this->generator->setRouterEnhancerPlugins($this->routerEnhancerPlugins);
        }

        return $this->generator;
    }
}
