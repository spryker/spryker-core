<?php
/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\UrlMatcher;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher as SymfonyRedirectableUrlMatcher;

class RedirectableUrlMatcher extends SymfonyRedirectableUrlMatcher
{

    /**
     * {@inheritdoc}
     */
    public function redirect($path, $route, $scheme = null)
    {
        $url = $this->context->getBaseUrl().$path;
        $query = $this->context->getQueryString() ?: '';

        if ($query !== '') {
            $url .= '?'.$query;
        }

        if ($this->context->getHost()) {
            if ($scheme) {
                $port = '';
                if ('http' === $scheme && 80 != $this->context->getHttpPort()) {
                    $port = ':'.$this->context->getHttpPort();
                } elseif ('https' === $scheme && 443 != $this->context->getHttpsPort()) {
                    $port = ':'.$this->context->getHttpsPort();
                }

                $url = $scheme.'://'.$this->context->getHost().$port.$url;
            }
        }

        return array(
            '_controller' => function ($url) { return new RedirectResponse($url, 301); },
            '_route' => null,
            'url' => $url,
        );
    }
}
