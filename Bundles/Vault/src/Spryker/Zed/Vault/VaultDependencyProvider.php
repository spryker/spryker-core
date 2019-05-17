<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Vault\Dependency\Service\VaultToUtilEncryptionServiceBridge;

/**
 * @method \Spryker\Zed\Vault\VaultConfig getConfig()
 */
class VaultDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_ENCRYPTION = 'SERVICE_UTIL_ENCRYPTION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addUtilEncryptionService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncryptionService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_ENCRYPTION] = function (Container $container) {
            return new VaultToUtilEncryptionServiceBridge(
                $container->getLocator()->utilEncryption()->service()
            );
        };

        return $container;
    }
}
