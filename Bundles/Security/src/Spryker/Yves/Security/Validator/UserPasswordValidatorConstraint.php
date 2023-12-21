<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Validator;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPasswordValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;

class UserPasswordValidatorConstraint implements UserPasswordValidatorConstraintInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_HASHER_FACTORY = 'security.hasher_factory';

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Validator\ConstraintValidatorInterface
     */
    public function getConstraintInstance(ContainerInterface $container): ConstraintValidatorInterface
    {
        return new UserPasswordValidator($this->getTokenStorage($container), $this->getHasherStorage($container));
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    protected function getTokenStorage(ContainerInterface $container): TokenStorageInterface
    {
        return $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface
     */
    protected function getHasherStorage(ContainerInterface $container): PasswordHasherFactoryInterface
    {
        return $container->get(static::SERVICE_SECURITY_HASHER_FACTORY);
    }
}
