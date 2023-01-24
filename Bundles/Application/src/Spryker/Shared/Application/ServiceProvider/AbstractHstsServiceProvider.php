<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * HTTP Strict Transport Security support as a ServiceProvider
 *
 * @see https://www.owasp.org/index.php/HTTP_Strict_Transport_Security
 */
abstract class AbstractHstsServiceProvider implements ServiceProviderInterface
{
    /**
     * @var string
     */
    public const HEADER_HSTS = 'Strict-Transport-Security';

    /**
     * @var string
     */
    public const HSTS_CONFIG_MAXAGE = 'max_age';

    /**
     * @var string
     */
    public const HSTS_CONFIG_INCLUDE_SUBDOMAINS = 'include_sub_domains';

    /**
     * @var string
     */
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
        /** @var \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher */
        $eventDispatcher = $app['dispatcher'];
        $eventDispatcher->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse'], 0);
    }

    /**
     * Sets security headers.
     *
     * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
     *
     * @return void
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        if (!$this->isMainRequest($event) || !$this->getIsHstsEnabled()) {
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
            $headerParts[] = 'max-age=' . $hstsConfig[static::HSTS_CONFIG_MAXAGE];
        }

        if (!empty($hstsConfig[static::HSTS_CONFIG_INCLUDE_SUBDOMAINS])) {
            $headerParts[] = 'includeSubDomains';
        }

        if (!empty($hstsConfig[static::HSTS_CONFIG_PRELOAD])) {
            $headerParts[] = 'preload';
        }

        if ($headerParts) {
            return implode('; ', $headerParts);
        }

        return '';
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
     *
     * @return bool
     */
    protected function isMainRequest(ResponseEvent $event): bool
    {
        if (method_exists($event, 'isMasterRequest')) {
            return $event->isMasterRequest();
        }

        return $event->isMainRequest();
    }
}
