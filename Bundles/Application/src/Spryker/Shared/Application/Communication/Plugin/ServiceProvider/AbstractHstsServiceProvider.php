<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * HTTP Strict Transport Security support as a ServiceProvider
 *
 * @see https://www.owasp.org/index.php/HTTP_Strict_Transport_Security
 *
 * @deprecated Use Spryker\Shared\Application\ServiceProvider\AbstractHstsServiceProvider instead
 */
abstract class AbstractHstsServiceProvider implements ServiceProviderInterface
{
    /**
     * @const string
     */
    public const HEADER_HSTS = 'Strict-Transport-Security';
    public const HSTS_CONFIG_MAXAGE = 'max_age';
    public const HSTS_CONFIG_INCLUDE_SUBDOMAINS = 'include_sub_domains';
    public const HSTS_CONFIG_PRELOAD = 'preload';

    /**
     * @return bool
     */
    abstract protected function getIsHstsEnabled();

    /**
     * @return array
     */
    abstract protected function getHstsConfig();

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $app['dispatcher']->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse'], 0);
    }

    /**
     * Sets security headers.
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     *
     * @return void
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest() || !$this->getIsHstsEnabled()) {
            return;
        }
        $headerBody = $this->renderHeaderBody($this->getHstsConfig());
        if ($headerBody !== '') {
            $event->getResponse()->headers->set(static::HEADER_HSTS, $headerBody);
        }
    }

    /**
     * @param array $hstsConfig
     *
     * @return string
     */
    protected function renderHeaderBody($hstsConfig)
    {
        $headerParts = [];
        if (!empty($hstsConfig[static::HSTS_CONFIG_MAXAGE])) {
            $headerParts[] = "max-age=" . $hstsConfig[static::HSTS_CONFIG_MAXAGE];
        }

        if (!empty($hstsConfig[static::HSTS_CONFIG_INCLUDE_SUBDOMAINS])) {
            $headerParts[] = "includeSubDomains";
        }

        if (!empty($hstsConfig[static::HSTS_CONFIG_PRELOAD])) {
            $headerParts[] = "preload";
        }

        if ($headerParts) {
            return implode('; ', $headerParts);
        }

        return '';
    }
}
