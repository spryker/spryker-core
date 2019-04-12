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
class LanguagePrefixRouterEnhancerPlugin extends AbstractRouterEnhancerPlugin
{
    /**
     * @var array
     */
    protected $allowedLanguages = [
        'de',
        'en',
    ];

    /**
     * @var string|null
     */
    protected $currentLanguage;

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
        if (in_array($pathinfoFragments[0], $this->allowedLanguages)) {
            $this->currentLanguage = array_shift($pathinfoFragments);

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
        if ($this->currentLanguage !== null) {
            $parameters['language'] = $this->currentLanguage;
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
        $language = $this->findLanguage($requestContext);

        if ($language !== null) {
            if ($url === '/') {
                $url = '';
            }

            return sprintf('/%s%s', $language, $url);
        }

        return $url;
    }

    /**
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     *
     * @return string|null
     */
    protected function findLanguage(RequestContext $requestContext): ?string
    {
        if ($requestContext->hasParameter('language')) {
            return $requestContext->getParameter('language');
        }

        return null;
    }
}
