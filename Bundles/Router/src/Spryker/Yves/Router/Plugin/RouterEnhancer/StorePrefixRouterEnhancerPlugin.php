<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\RouterEnhancer;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Yves\Router\Router\Router;
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
     * @param int $referenceType
     *
     * @return string
     */
    public function afterGenerate(string $url, RequestContext $requestContext, int $referenceType): string
    {
        $store = $this->findStore($requestContext);

        if ($store !== null) {
            return $this->buildUrlWithStore($url, $store, $referenceType);
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

    /**
     * @param string $url
     * @param string $store
     * @param int $referenceType
     *
     * @return string
     */
    protected function buildUrlWithStore(string $url, string $store, int $referenceType): string
    {
        if ($url === '/') {
            $url = '';
        }

        if ($referenceType === Router::ABSOLUTE_PATH) {
            return sprintf('/%s%s', $store, $url);
        }

        if ($referenceType === Router::ABSOLUTE_URL) {
            $parsedUrl = Url::parse($url);
            $pathWithStore = sprintf('/%s%s', $store, $parsedUrl->getPath());
            $parsedUrl->setPath($pathWithStore);

            return (string)$parsedUrl;
        }

        return $url;
    }
}
