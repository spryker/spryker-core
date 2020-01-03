<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionFile;

use Spryker\Shared\SessionFile\Dependency\Service\SessionFileToMonitoringServiceBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * @method \Spryker\Yves\SessionFile\SessionFileConfig getConfig()
 */
class SessionFileDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_MONITORING = 'SERVICE_MONITORING';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addMonitoringService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
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
