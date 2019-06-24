<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\UrlMatcher;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Matcher\CompiledUrlMatcher as SymfonyCompiledUrlMatcher;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcherInterface;

class CompiledUrlMatcher extends SymfonyCompiledUrlMatcher implements RedirectableUrlMatcherInterface
{
    /**
     * @param string $path
     * @param string $route
     * @param string|null $scheme
     *
     * @return array
     */
    public function redirect($path, $route, $scheme = null): array
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

                $url = sprintf('%s://%s%s%s', $scheme, $this->context->getHost(), $port, $url);
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
