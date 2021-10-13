<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Http\Plugin\EventDispatcher;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security
 *
 * @method \Spryker\Glue\Http\HttpConfig getConfig()
 */
class StrictTransportSecurityHeaderEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * @var string
     */
    protected const HEADER_STS = 'Strict-Transport-Security';
    /**
     * @var string
     */
    protected const HEADER_STS_MAX_AGE = 'max_age';
    /**
     * @var string
     */
    protected const HEADER_STS_INCLUDE_SUBDOMAINS = 'include_sub_domains';
    /**
     * @var string
     */
    protected const HEADER_STS_PRELOAD = 'preload';

    /**
     * {@inheritDoc}
     * - Sets `Strict-Transport-Security` header to response.
     *
     * @api
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addListener(KernelEvents::RESPONSE, function (ResponseEvent $event): void {
            if (!$event->isMasterRequest() || !$this->getConfig()->isStrictTransportSecurityEnabled()) {
                return;
            }

            $event->setResponse($this->setStrictTransportSecurityHeader($event->getResponse()));
        });

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function setStrictTransportSecurityHeader(Response $response): Response
    {
        $headerBody = $this->buildHeaderBody($this->getConfig()->getStrictTransportSecurityConfig());
        if ($headerBody !== '') {
            $response->headers->set(static::HEADER_STS, $headerBody);
        }

        return $response;
    }

    /**
     * @phpstan-param array<string, mixed> $strictTransportSecurityConfig
     *
     * @param array<string> $strictTransportSecurityConfig
     *
     * @return string
     */
    protected function buildHeaderBody(array $strictTransportSecurityConfig): string
    {
        $headerParts = [];
        if (!empty($strictTransportSecurityConfig[static::HEADER_STS_MAX_AGE])) {
            $headerParts[] = sprintf('max-age=%s', $strictTransportSecurityConfig[static::HEADER_STS_MAX_AGE]);
        }

        if (!empty($strictTransportSecurityConfig[static::HEADER_STS_INCLUDE_SUBDOMAINS])) {
            $headerParts[] = 'includeSubDomains';
        }

        if (!empty($strictTransportSecurityConfig[static::HEADER_STS_PRELOAD])) {
            $headerParts[] = 'preload';
        }

        return implode('; ', $headerParts);
    }
}
