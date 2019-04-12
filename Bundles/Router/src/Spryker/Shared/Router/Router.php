<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Router;

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
     * @param string $name
     * @param array $parameters
     * @param int $referenceType
     *
     * @return string
     */
    public function generate($name, $parameters = [], $referenceType = SymfonyRouter::ABSOLUTE_PATH)
    {
        $generatedUrl = parent::generate($name, $parameters, $referenceType);

        foreach (array_reverse($this->routerEnhancerPlugins) as $routerEnhancerPlugin) {
            $generatedUrl = $routerEnhancerPlugin->afterGenerate($generatedUrl, $this->getContext());
        }

        return $generatedUrl;
    }

    /**
     * @param string $pathinfo
     *
     * @return array
     */
    public function match($pathinfo)
    {
        foreach ($this->routerEnhancerPlugins as $routerEnhancerPlugin) {
            $pathinfo = $routerEnhancerPlugin->beforeMatch($pathinfo, $this->getContext());
        }

        $parameters = parent::match($pathinfo);

        foreach ($this->routerEnhancerPlugins as $routerEnhancerPlugin) {
            $parameters = $routerEnhancerPlugin->afterMatch($parameters, $this->getContext());
        }

        return $parameters;
    }
}
