<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Plugin\Validator;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ValidatorExtension\Dependency\Plugin\ConstraintPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Validator\ConstraintValidatorInterface;

/**
 * @method \Spryker\Yves\Security\SecurityFactory getFactory()
 */
class YvesUserPasswordValidatorConstraintPlugin extends AbstractPlugin implements ConstraintPluginInterface
{
    /**
     * @var string
     */
    protected const CONSTRAINT_NAME = 'security.validator.user_password';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::CONSTRAINT_NAME;
    }

    /**
     * {@inheritDoc}
     * - Returns an instance of {@link \Symfony\Component\Security\Core\Validator\Constraints\UserPasswordValidator} constraint.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Validator\ConstraintValidatorInterface
     */
    public function getConstraintInstance(ContainerInterface $container): ConstraintValidatorInterface
    {
        return $this->getFactory()->createUserPasswordValidatorConstraint()->getConstraintInstance($container);
    }
}
