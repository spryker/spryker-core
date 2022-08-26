<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application\Expander;

use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Yves\Application\ApplicationConfig;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SecurityHeaderExpander implements SecurityHeaderExpanderInterface
{
    /**
     * @var \Spryker\Yves\Application\ApplicationConfig
     */
    protected ApplicationConfig $applicationConfig;

    /**
     * @var array<\Spryker\Yves\ApplicationExtension\Dependency\Plugin\SecurityHeaderExpanderPluginInterface>
     */
    protected array $securityHeaderExpanderPlugins;

    /**
     * @param \Spryker\Yves\Application\ApplicationConfig $applicationConfig
     * @param array<\Spryker\Yves\ApplicationExtension\Dependency\Plugin\SecurityHeaderExpanderPluginInterface> $securityHeaderExpanderPlugins
     */
    public function __construct(ApplicationConfig $applicationConfig, array $securityHeaderExpanderPlugins)
    {
        $this->applicationConfig = $applicationConfig;
        $this->securityHeaderExpanderPlugins = $securityHeaderExpanderPlugins;
    }

    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function expand(EventDispatcherInterface $eventDispatcher): EventDispatcherInterface
    {
        $securityHeaders = $this->applicationConfig->getSecurityHeaders();
        $securityHeaders = $this->executeSecurityHeaderExpanderPlugins($securityHeaders);

        $eventDispatcher->addListener(
            KernelEvents::RESPONSE,
            function (ResponseEvent $responseEvent) use ($securityHeaders) {
                foreach ($securityHeaders as $securityHeaderName => $securityHeaderValue) {
                    if ($securityHeaderValue) {
                        $responseEvent->getResponse()->headers->set($securityHeaderName, $securityHeaderValue);
                    }
                }
            },
        );

        return $eventDispatcher;
    }

    /**
     * @param array<string, string> $securityHeaders
     *
     * @return array<string, string>
     */
    protected function executeSecurityHeaderExpanderPlugins(array $securityHeaders): array
    {
        foreach ($this->securityHeaderExpanderPlugins as $securityHeaderExpanderPlugin) {
            $securityHeaders = $securityHeaderExpanderPlugin->expand($securityHeaders);
        }

        return $securityHeaders;
    }
}
