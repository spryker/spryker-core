<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Validator;

use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Symfony\Component\Validator\Mapping\Loader\LoaderInterface;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;
use Symfony\Component\Validator\ValidatorBuilder;

class ValidatorFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Validator\ValidatorBuilder
     */
    public function createValidatorBuilder(): ValidatorBuilder
    {
        return new ValidatorBuilder();
    }

    /**
     * @return \Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface
     */
    public function createValidatorMappingMetadataFactory(): MetadataFactoryInterface
    {
        return new LazyLoadingMetadataFactory($this->createStaticMethodLoader());
    }

    /**
     * @return \Symfony\Component\Validator\Mapping\Loader\LoaderInterface
     */
    public function createStaticMethodLoader(): LoaderInterface
    {
        return new StaticMethodLoader();
    }

    /**
     * @return \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ValidatorPluginInterface[]
     */
    public function getValidatorPlugins(): array
    {
        return $this->getProvidedDependency(ValidatorDependencyProvider::PLUGINS_VALIDATOR);
    }

    /**
     * @return \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ConstraintPluginInterface[]
     */
    public function getConstraintPlugins(): array
    {
        return $this->getProvidedDependency(ValidatorDependencyProvider::PLUGINS_CONSTRAINT);
    }
}
