<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Validator;

use Spryker\Shared\Validator\Plugin\Validator\MetadataFactoryValidatorPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ValidatorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_VALIDATOR = 'PLUGINS_VALIDATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addValidatorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
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
        return [
            new MetadataFactoryValidatorPlugin(),
        ];
    }
}
