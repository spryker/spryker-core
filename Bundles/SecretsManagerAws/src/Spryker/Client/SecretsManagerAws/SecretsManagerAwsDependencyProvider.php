<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecretsManagerAws;

use Aws\SecretsManager\SecretsManagerClient;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\SecretsManagerAws\Dependency\Service\SecretsManagerAwsToUtilTextServiceBridge;

/**
 * @method \Spryker\Client\SecretsManagerAws\SecretsManagerAwsConfig getConfig()
 */
class SecretsManagerAwsDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SECRETS_MANAGER_AWS = 'CLIENT_SECRETS_MANAGER_AWS';

    /**
     * @var string
     */
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addSecretsManagerAwsClient($container);
        $container = $this->addUtilTextService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSecretsManagerAwsClient(Container $container): Container
    {
        $container->set(static::CLIENT_SECRETS_MANAGER_AWS, function () {
            return new SecretsManagerClient([
                'credentials' => [
                    'key' => $this->getConfig()->getSecretsManagerAwsAccessKey(),
                    'secret' => $this->getConfig()->getSecretsManagerAwsAccessSecret(),
                ],
                'endpoint' => $this->getConfig()->getSecretsManagerAwsEndpoint(),
                'region' => $this->getConfig()->getSecretsManagerAwsRegion(),
                'version' => $this->getConfig()->getSecretsManagerAwsVersion(),
            ]);
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new SecretsManagerAwsToUtilTextServiceBridge(
                $container->getLocator()->utilText()->service(),
            );
        });

        return $container;
    }
}
