<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Loader\Services;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EncoderServiceLoader implements ServiceLoaderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_HASHER_FACTORY = 'security.hasher_factory';

    /**
     * @var \Symfony\Component\PasswordHasher\PasswordHasherInterface
     */
    protected PasswordHasherInterface $passwordHasher;

    /**
     * @param \Symfony\Component\PasswordHasher\PasswordHasherInterface $passwordHasher
     */
    public function __construct(PasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function add(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_HASHER_FACTORY, function (): PasswordHasherFactoryInterface {
            return new PasswordHasherFactory([
                UserInterface::class => $this->passwordHasher,
            ]);
        });

        return $container;
    }
}
