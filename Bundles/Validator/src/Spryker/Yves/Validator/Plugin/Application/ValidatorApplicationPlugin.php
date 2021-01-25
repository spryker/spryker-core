<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Validator\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Validator\ValidatorBuilder;

/**
 * @method \Spryker\Yves\Validator\ValidatorFactory getFactory()
 */
class ValidatorApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_VALIDATOR = 'validator';

    /**
     * {@inheritDoc}
     * - Adds `validator` service.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addValidatorService($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addValidatorService(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_VALIDATOR, function (ContainerInterface $container) {
            $validatorBuilder = $this->getFactory()->createValidatorBuilder();

            $validatorBuilder = $this->extendValidator($validatorBuilder, $container);

            return $validatorBuilder->getValidator();
        });

        return $container;
    }

    /**
     * @param \Symfony\Component\Validator\ValidatorBuilder $validatorBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Validator\ValidatorBuilder
     */
    protected function extendValidator(ValidatorBuilder $validatorBuilder, ContainerInterface $container): ValidatorBuilder
    {
        foreach ($this->getFactory()->getValidatorPlugins() as $validatorPlugin) {
            $validatorBuilder = $validatorPlugin->extend($validatorBuilder, $container);
        }

        return $validatorBuilder;
    }
}
