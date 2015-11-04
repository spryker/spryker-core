<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Application\Routing;

use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractRouter implements RouterInterface
{

    /**
     * @var RequestContext
     */
    protected $context;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var bool
     */
    private $sslEnabled;

    /**
     * Set the sslEnabledFlag to
     *     true to force ssl
     *     false to force http
     *     null to not force anything (both https or http allowed)
     *
     * @param Application $app
     * @param bool|null $sslEnabled
     */
    public function __construct(Application $app, $sslEnabled = null)
    {
        $this->app = $app;
        $this->sslEnabled = $sslEnabled;
    }

    /**
     * {@inheritdoc}
     */
    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection()
    {
        return new RouteCollection();
    }

    /**
     * @return bool
     */
    public function isSslEnabled()
    {
        return $this->sslEnabled;
    }

    /**
     * @param string $pathInfo
     *
     * @return array|null
     */
    protected function checkScheme($pathInfo)
    {
        $wantedScheme = $this->isSslEnabled() ? 'https' : 'http';
        if ($this->getContext()->getScheme() !== $wantedScheme) {
            $url = $wantedScheme . '://' . $this->context->getHost() . $pathInfo;

            return [
                '_controller' => function ($url) {
                    return new RedirectResponse($url, 301);
                },
                '_route' => null,
                'url' => $url,
            ];
        }

        return null;
    }

    /**
     * @param string $pathInfo
     * @param bool|string $referenceType
     *
     * @return string
     */
    protected function getUrlOrPathForType($pathInfo, $referenceType)
    {
        $url = $pathInfo;
        $scheme = $this->context->getScheme();

        if (self::NETWORK_PATH !== $referenceType &&
            ('http' === $scheme && true === $this->sslEnabled || 'https' === $scheme && false === $this->sslEnabled)) {
            $referenceType = self::ABSOLUTE_URL;
        }

        switch ($referenceType) {
            case self::ABSOLUTE_URL:
            case self::NETWORK_PATH:
                $url = $this->buildUrl($pathInfo, $referenceType);
                break;
            case self::ABSOLUTE_PATH:
                $url = $pathInfo;
                break;
            case self::RELATIVE_PATH:
                $url = UrlGenerator::getRelativePath($this->context->getPathInfo(), $pathInfo);
                break;
        }

        return $url;
    }

    /**
     * @param string $pathInfo
     * @param bool|string $referenceType
     *
     * @return string
     */
    private function buildUrl($pathInfo, $referenceType)
    {
        $scheme = $this->getScheme();
        $port = $this->getPortPart($scheme);
        $schemeAuthority = self::NETWORK_PATH === $referenceType ? '//' : "$scheme://";
        $schemeAuthority .= $this->context->getHost() . $port;

        return $schemeAuthority . $this->context->getBaseUrl() . $pathInfo;
    }

    /**
     * @param string $scheme
     *
     * @return string
     */
    private function getPortPart($scheme)
    {
        $port = '';
        if ('http' === $scheme && 80 !== $this->context->getHttpPort()) {
            $port = ':' . $this->context->getHttpPort();
        } elseif ('https' === $scheme && 443 !== $this->context->getHttpsPort()) {
            $port = ':' . $this->context->getHttpsPort();
        }

        return $port;
    }

    /**
     * @return string
     */
    private function getScheme()
    {
        $scheme = $this->context->getScheme();
        if (is_bool($this->sslEnabled)) {
            $scheme = ($this->sslEnabled) ? 'https' : 'http';
        }

        return $scheme;
    }

}
