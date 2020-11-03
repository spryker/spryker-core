<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ChecksumGenerator;

use Spryker\Service\ChecksumGenerator\Dependency\Service\ChecksumGeneratorToUtilEncryptionServiceBridge;
use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

class ChecksumGeneratorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_ENCRYPTION = 'SERVICE_UTIL_ENCRYPTION';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container): Container
    {
        $container = parent::provideServiceDependencies($container);

        $container = $this->addUtilEncryptionService($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addUtilEncryptionService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCRYPTION, function (Container $container) {
            return new ChecksumGeneratorToUtilEncryptionServiceBridge(
                $container->getLocator()->utilEncryption()->service()
            );
        });

        return $container;
    }
}
