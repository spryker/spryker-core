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

abstract class AbstractHSTSServiceProvider implements ServiceProviderInterface
{

    /**
     * @const string
     */
    const HEADER_HSTS = 'Strict-Transport-Security';
    const HSTS_CONFIG_MAXAGE = 'max_age';
    const HSTS_CONFIG_INCLUDE_SUBDOMAINS = 'include_sub_domains';
    const HSTS_CONFIG_PRELOAD = 'preload';

    /**
     * @return boolean
     */
    abstract protected function getIsHSTSEnabled();

    /**
     * @return array
     */
    abstract protected function getHSTSConfig();

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
        if (!$event->isMasterRequest() || !$this->getIsHSTSEnabled()) {
            return;
        }
        $headerBody = $this->renderHeaderBody($this->getHSTSConfig());
        if (strlen($headerBody)) {
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

        if (!empty($headerParts)) {
            return implode('; ', $headerParts);
        }

        return '';
    }

}
