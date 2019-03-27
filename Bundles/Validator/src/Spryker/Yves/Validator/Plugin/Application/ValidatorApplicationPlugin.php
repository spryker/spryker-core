<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Validator\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Validator\ValidatorBuilderInterface;

/**
 * @method \Spryker\Yves\Validator\ValidatorFactory getFactory()
 */
class ValidatorApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_VALIDATOR = 'validator';
    protected const SERVICE_VALIDATOR_SERVICE_IDS = 'validator.validator_service_ids';

    /**
     * {@inheritdoc}
     * - Adds global `validator` service.
     * - Adds `validator.validator_service_ids` service.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_VALIDATOR_SERVICE_IDS, function () {
            return [];
        });

        $container->setGlobal(static::SERVICE_VALIDATOR, function (ContainerInterface $container) {
            $validatorBuilder = $this->getFactory()->createValidationBuilder();

            $validatorBuilder = $this->extendValidator($validatorBuilder, $container);

            return $validatorBuilder->getValidator();
        });

        return $container;
    }

    /**
     * @param \Symfony\Component\Validator\ValidatorBuilderInterface $validatorBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Validator\ValidatorBuilderInterface
     */
    protected function extendValidator(ValidatorBuilderInterface $validatorBuilder, ContainerInterface $container): ValidatorBuilderInterface
    {
        foreach ($this->getFactory()->getValidatorPlugins() as $validatorPlugin) {
            $validatorBuilder = $validatorPlugin->extend($validatorBuilder, $container);
        }

        return $validatorBuilder;
    }
}
