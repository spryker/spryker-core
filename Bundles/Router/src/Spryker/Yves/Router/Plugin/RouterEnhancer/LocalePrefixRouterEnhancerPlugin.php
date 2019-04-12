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
class LocalePrefixRouterEnhancerPlugin extends AbstractRouterEnhancerPlugin
{
    /**
     * @var array
     */
    protected $allowedLocales = [
        'de',
        'en',
    ];

    /**
     * @var string|null
     */
    protected $currentLocale;

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
        if (in_array($pathinfoFragments[0], $this->allowedLocales)) {
            $this->currentLocale = array_shift($pathinfoFragments);

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
        if ($this->currentLocale !== null) {
            $parameters['locale'] = $this->currentLocale;
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
        if ($url === '/') {
            return $url;
        }

        $locale = $this->findLocale($requestContext);

        if ($locale !== null) {
            return sprintf('/%s%s', $locale, $url);
        }

        return $url;
    }

    /**
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     *
     * @return string|null
     */
    protected function findLocale(RequestContext $requestContext): ?string
    {
        if ($requestContext->hasParameter('locale')) {
            return $requestContext->getParameter('locale');
        }

        return null;
    }
}
