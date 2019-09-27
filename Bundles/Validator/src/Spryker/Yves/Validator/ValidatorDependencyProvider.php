<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Validator;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Security\Plugin\Validator\UserPasswordValidatorConstraintPlugin;
use Spryker\Yves\Validator\Plugin\Validator\ConstraintValidatorFactoryValidatorPlugin;
use Spryker\Yves\Validator\Plugin\Validator\MetadataFactoryValidatorPlugin;

class ValidatorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_VALIDATOR = 'PLUGINS_VALIDATOR';
    public const PLUGINS_CORE_VALIDATOR = 'PLUGINS_CORE_VALIDATOR';

    public const PLUGINS_CONSTRAINT = 'PLUGINS_CONSTRAINT';
    public const PLUGINS_CORE_CONSTRAINT = 'PLUGINS_CORE_CONSTRAINT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addValidatorPlugins($container);
        $container = $this->addCoreValidatorPlugins($container);

        $container = $this->addConstraintPlugins($container);
        $container = $this->addCoreConstraintPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_VALIDATOR, function () {
            return $this->getValidatorPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ValidatorPluginInterface[]
     */
    protected function getValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCoreValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CORE_VALIDATOR, function () {
            return $this->getCoreValidatorPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ValidatorPluginInterface[]
     */
    protected function getCoreValidatorPlugins(): array
    {
        return [
            new MetadataFactoryValidatorPlugin(),
            new ConstraintValidatorFactoryValidatorPlugin(),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addConstraintPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONSTRAINT, function () {
            return $this->getConstraintPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ConstraintPluginInterface[]
     */
    protected function getConstraintPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCoreConstraintPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CORE_CONSTRAINT, function () {
            return $this->getCoreConstraintPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\ValidatorExtension\Dependency\Plugin\ConstraintPluginInterface[]
     */
    protected function getCoreConstraintPlugins(): array
    {
        return [
            new UserPasswordValidatorConstraintPlugin(),
        ];
    }
}
