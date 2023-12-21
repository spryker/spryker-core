<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Loader\Services;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Zed\Security\SecurityConfig;
use Symfony\Component\Security\Http\Firewall\ChannelListener;
use Symfony\Component\Security\Http\Firewall\FirewallListenerInterface;

class ChannelListenerServiceLoader implements ServiceLoaderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_CHANNEL_LISTENER = 'security.channel_listener';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_ACCESS_MAP = 'security.access_map';

    /**
     * @var string
     */
    protected const SERVICE_LOGGER = 'logger';

    /**
     * @var \Spryker\Zed\Security\SecurityConfig
     */
    protected SecurityConfig $config;

    /**
     * @param \Spryker\Zed\Security\SecurityConfig $config
     */
    public function __construct(SecurityConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function add(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_CHANNEL_LISTENER, function (ContainerInterface $container): FirewallListenerInterface {
            return new ChannelListener(
                $container->get(static::SERVICE_SECURITY_ACCESS_MAP),
                $container->has(static::SERVICE_LOGGER) ? $container->get(static::SERVICE_LOGGER) : null,
                $this->config->getHttpPort(),
                $this->config->getHttpsPort(),
            );
        });

        return $container;
    }
}
