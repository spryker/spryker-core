<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Validator\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\ValidatorBuilderInterface;

class ValidatorApplicationPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_VALIDATOR = 'validator';
    protected const SERVICE_VALIDATOR_SERVICE_IDS = 'validator.validator_service_ids';

    protected const TRANSLATION_DOMAIN = 'validators';

    protected const SERVICE_TRANSLATOR = 'translator';

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
            return $this->createValidatorBuilder($container)->getValidator();
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Validator\ValidatorBuilderInterface
     */
    protected function createValidatorBuilder(ContainerInterface $container): ValidatorBuilderInterface
    {
        $builder = Validation::createValidatorBuilder();
        $builder->setMetadataFactory($this->createValidatorMappingMetadataFactory());
        if ($container->has(static::SERVICE_TRANSLATOR)) {
            $builder->setTranslationDomain(static::TRANSLATION_DOMAIN);
            $builder->setTranslator($container->get(static::SERVICE_TRANSLATOR));
        }

        return $builder;
    }

    /**
     * @return \Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface
     */
    protected function createValidatorMappingMetadataFactory(): MetadataFactoryInterface
    {
        return new LazyLoadingMetadataFactory(new StaticMethodLoader());
    }
}
