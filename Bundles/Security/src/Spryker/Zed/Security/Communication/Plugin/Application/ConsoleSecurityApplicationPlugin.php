<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Security\Communication\SecurityCommunicationFactory getFactory()
 * @method \Spryker\Zed\Security\SecurityConfig getConfig()()
 * @method \Spryker\Zed\Security\Business\SecurityFacadeInterface getFacade()
 */
class ConsoleSecurityApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface, BootableApplicationPluginInterface
{
    /**
     * @uses \Spryker\Zed\Security\Communication\Loader\Services\AuthorizationCheckerServiceLoader::SERVICE_SECURITY_AUTHORIZATION_CHECKER
     *
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHORIZATION_CHECKER = 'security.authorization_checker';

    /**
     * {@inheritDoc}
     * - Provides authorization checker service for console commands.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function boot(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER, function () {
            return null;
        });

        return $container;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        return $container;
    }
}
