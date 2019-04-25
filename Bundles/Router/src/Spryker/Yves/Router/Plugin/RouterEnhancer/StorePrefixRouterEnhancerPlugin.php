<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\RouterEnhancer;

use Symfony\Component\Routing\RequestContext;

/**
 * @method \Spryker\Yves\Router\RouterConfig getConfig()
 */
class StorePrefixRouterEnhancerPlugin extends AbstractRouterEnhancerPlugin
{
    /**
     * @var string|null
     */
    protected $currentStore;

    /**
     * @param string $pathinfo
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     *
     * @return string
     */
    public function beforeMatch(string $pathinfo, RequestContext $requestContext): string
    {
        if ($pathinfo === '/') {
            return $pathinfo;
        }

        $pathinfoFragments = explode('/', trim($pathinfo, '/'));
        if (in_array($pathinfoFragments[0], $this->getConfig()->getAllowedStores())) {
            $this->currentStore = array_shift($pathinfoFragments);

            return '/' . implode('/', $pathinfoFragments);
        }

        return $pathinfo;
    }

    /**
     * @param array $parameters
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     *
     * @return array
     */
    public function afterMatch(array $parameters, RequestContext $requestContext): array
    {
        if ($this->currentStore !== null) {
            $parameters['store'] = $this->currentStore;
        }

        return $parameters;
    }

    /**
     * @param string $url
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     *
     * @return string
     */
    public function afterGenerate(string $url, RequestContext $requestContext): string
    {
        $store = $this->findStore($requestContext);
        if ($url === '/') {
        }
        if ($store !== null) {
            if ($url === '/') {
                $url = '';
            }

            return sprintf('/%s%s', $store, $url);
        }

        return $url;
    }

    /**
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     *
     * @return string|null
     */
    protected function findStore(RequestContext $requestContext): ?string
    {
        if ($requestContext->hasParameter('store')) {
            return $requestContext->getParameter('store');
        }

        return null;
    }
}
