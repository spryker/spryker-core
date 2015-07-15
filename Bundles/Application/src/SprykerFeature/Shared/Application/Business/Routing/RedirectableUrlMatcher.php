<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Silex\Routing;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcherInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Route;

class RedirectableUrlMatcher extends UrlMatcher implements RedirectableUrlMatcherInterface
{

    /**
     * {@inheritdoc}
     */
    public function match($pathinfo)
    {
        try {
            return parent::match($pathinfo);
        } catch (ResourceNotFoundException $e) {
            if (strrchr($pathinfo, '.') === '.html' || !in_array($this->context->getMethod(), ['HEAD', 'GET'])) {
                throw $e;
            }

            if ('/' === substr($pathinfo, -1)) {
                $pathInfoForRedirect = substr($pathinfo, 0, -1);
            } else {
                $pathInfoForRedirect = $pathinfo . '/';
            }

            try {
                parent::match($pathInfoForRedirect);

                return $this->redirect($pathInfoForRedirect, null);
            } catch (ResourceNotFoundException $e2) {
                throw $e;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function handleRouteRequirements($pathinfo, $name, Route $route)
    {
        // check HTTP scheme requirement
        $scheme = $route->getRequirement('_scheme');
        if ($scheme && $this->context->getScheme() !== $scheme) {
            return [self::ROUTE_MATCH, $this->redirect($pathinfo, $name, $scheme)];
        }

        return [self::REQUIREMENT_MATCH, null];
    }

    /**
     * @see RedirectableUrlMatcherInterface::match()
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
                if ('http' === $scheme && 80 !== $this->context->getHttpPort()) {
                    $port = ':' . $this->context->getHttpPort();
                } elseif ('https' === $scheme && 443 !== $this->context->getHttpsPort()) {
                    $port = ':' . $this->context->getHttpsPort();
                }

                $url = $scheme . '://' . $this->context->getHost() . $port . $url;
            }
        }

        return [
            '_controller' => function ($url) { return new RedirectResponse($url, 301); },
            '_route' => null,
            'url' => $url,
        ];
    }

}
