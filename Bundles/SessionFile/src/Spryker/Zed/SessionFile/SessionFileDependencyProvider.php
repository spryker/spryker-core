<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionFile;

use Spryker\Shared\SessionFile\Dependency\Service\SessionFileToMonitoringServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\SessionFile\SessionFileConfig getConfig()
 */
class SessionFileDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_MONITORING = 'SERVICE_MONITORING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addMonitoringService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMonitoringService(Container $container): Container
    {
        $container->set(static::SERVICE_MONITORING, function (Container $container) {
            return new SessionFileToMonitoringServiceBridge(
                $container->getLocator()->monitoring()->service()
            );
        });

        return $container;
    }
}
