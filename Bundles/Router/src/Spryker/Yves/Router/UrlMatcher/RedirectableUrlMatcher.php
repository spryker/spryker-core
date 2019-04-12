<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\UrlMatcher;

use Spryker\Shared\RouterExtension\Dependency\Plugin\RouterEnhancerAwareInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher as SymfonyRedirectableUrlMatcher;

class RedirectableUrlMatcher extends SymfonyRedirectableUrlMatcher implements RouterEnhancerAwareInterface
{
    /**
     * @var \Spryker\Shared\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface[]
     */
    protected $routerEnhancerPlugins;

    /**
     * @param array $routerEnhancerPlugins
     *
     * @return void
     */
    public function setRouterEnhancerPlugins(array $routerEnhancerPlugins): void
    {
        $this->routerEnhancerPlugins = $routerEnhancerPlugins;
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

    /**
     * {@inheritdoc}
     */
    public function redirect($path, $route, $scheme = null)
    {
        $url = $this->context->getBaseUrl() . $path;
        $query = $this->context->getQueryString() ?: '';

        if ($query !== '') {
            $url .= '?' . $query;
        }

        if ($this->context->getHost()) {
            if ($scheme) {
                $port = '';
                if ($scheme === 'http' && $this->context->getHttpPort() != 80) {
                    $port = ':' . $this->context->getHttpPort();
                } elseif ($scheme === 'https' && $this->context->getHttpsPort() != 443) {
                    $port = ':' . $this->context->getHttpsPort();
                }

                $url = $scheme . '://' . $this->context->getHost() . $port . $url;
            }
        }

        return [
            '_controller' => function ($url) {
                return new RedirectResponse($url, 301);
            },
            '_route' => null,
            'url' => $url,
        ];
    }
}
