<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Security\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\TokenStorage\ClearableTokenStorageInterface;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;

class SecurityApplicationPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_CSRF_PROVIDER = 'form.csrf_provider';
    protected const SERVICE_SESSION = 'session';

    /**
     * {@inheritdoc}
     * - Adds `form.csrf_provider` service.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container->setGlobal(static::SERVICE_CSRF_PROVIDER, function (ContainerInterface $container) {
            return $this->createCsrfTokenManager($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface
     */
    protected function createCsrfTokenManager(ContainerInterface $container): CsrfTokenManagerInterface
    {
        return new CsrfTokenManager(
            null,
            $this->createTokenStorage($container)
        );
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Security\Csrf\TokenStorage\ClearableTokenStorageInterface
     */
    protected function createTokenStorage(ContainerInterface $container): ClearableTokenStorageInterface
    {
        return new SessionTokenStorage($container->get(static::SERVICE_SESSION));
    }
}
