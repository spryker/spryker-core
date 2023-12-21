<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Loader\Services;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class AccessManagerServiceLoader implements ServiceLoaderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_ACCESS_MANAGER = 'security.access_manager';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_VOTERS = 'security.voters';

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function add(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_ACCESS_MANAGER, function (ContainerInterface $container): AccessDecisionManagerInterface {
            return new AccessDecisionManager($container->get(static::SERVICE_SECURITY_VOTERS));
        });

        return $container;
    }
}
