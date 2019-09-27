<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Validator\Plugin\Validator;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Validator\ConstraintValidatorFactory\ConstraintValidatorFactory;
use Spryker\Shared\ValidatorExtension\Dependency\Plugin\ValidatorPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ValidatorBuilderInterface;

/**
 * @method \Spryker\Yves\Validator\ValidatorFactory getFactory()
 */
class ConstraintValidatorFactoryValidatorPlugin extends AbstractPlugin implements ValidatorPluginInterface
{
    protected const SERVICE_VALIDATOR_SERVICE_IDS = 'validator.validator_service_ids';

    /**
     * {@inheritDoc}
     * - Adds `Spryker\Shared\Validator\ConstraintValidatorFactory\ContainerConstraintValidatorFactory` as constraint validator factory.
     *
     * @api
     *
     * @param \Symfony\Component\Validator\ValidatorBuilderInterface $validatorBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Validator\ValidatorBuilderInterface
     */
    public function extend(ValidatorBuilderInterface $validatorBuilder, ContainerInterface $container): ValidatorBuilderInterface
    {
        $validatorBuilder->setConstraintValidatorFactory($this->createConstraintValidationFactory($container));

        return $validatorBuilder;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Validator\ConstraintValidatorFactoryInterface
     */
    protected function createConstraintValidationFactory(ContainerInterface $container): ConstraintValidatorFactoryInterface
    {
        return new ConstraintValidatorFactory($container, $this->getConstraintPlugins());
    }

    /**
     * @return \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ConstraintPluginInterface[]
     */
    protected function getConstraintPlugins(): array
    {
        return array_merge($this->getFactory()->getCoreConstraintPlugins(), $this->getFactory()->getConstraintPlugins());
    }
}
