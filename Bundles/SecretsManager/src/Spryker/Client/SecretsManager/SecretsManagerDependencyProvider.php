<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecretsManager;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\SecretsManager\Exception\MissingSecretsManagerProviderPluginException;
use Spryker\Client\SecretsManagerExtension\Dependency\Plugin\SecretsManagerProviderPluginInterface;

class SecretsManagerDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGIN_SECRETS_MANAGER_PROVIDER = 'PLUGIN_SECRETS_MANAGER_PROVIDER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addSecretsManagerProviderPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSecretsManagerProviderPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SECRETS_MANAGER_PROVIDER, function () {
            return $this->getSecretsManagerProviderPlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Client\SecretsManager\Exception\MissingSecretsManagerProviderPluginException
     *
     * @return \Spryker\Client\SecretsManagerExtension\Dependency\Plugin\SecretsManagerProviderPluginInterface
     */
    protected function getSecretsManagerProviderPlugin(): SecretsManagerProviderPluginInterface
    {
        throw new MissingSecretsManagerProviderPluginException();
    }
}
