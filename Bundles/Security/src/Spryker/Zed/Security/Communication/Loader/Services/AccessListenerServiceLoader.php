<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Loader\Services;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\Security\Http\Firewall\AccessListener;
use Symfony\Component\Security\Http\Firewall\FirewallListenerInterface;

class AccessListenerServiceLoader implements ServiceLoaderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_ACCESS_MANAGER = 'security.access_manager';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_ACCESS_MAP = 'security.access_map';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_ACCESS_LISTENER = 'security.access_listener';

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function add(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_ACCESS_LISTENER, function (ContainerInterface $container): FirewallListenerInterface {
            return new AccessListener(
                $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE),
                $container->get(static::SERVICE_SECURITY_ACCESS_MANAGER),
                $container->get(static::SERVICE_SECURITY_ACCESS_MAP),
            );
        });

        return $container;
    }
}
