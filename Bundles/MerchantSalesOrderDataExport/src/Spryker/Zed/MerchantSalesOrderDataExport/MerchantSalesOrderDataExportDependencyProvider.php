<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service\MerchantSalesOrderDataExportToDataExportServiceBridge;

class MerchantSalesOrderDataExportDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_DATA_EXPORT = 'SERVICE_DATA_EXPORT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addDataExportService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataExportService(Container $container): Container
    {
        $container->set(static::SERVICE_DATA_EXPORT, function (Container $container) {
            return new MerchantSalesOrderDataExportToDataExportServiceBridge(
                $container->getLocator()->dataExport()->service()
            );
        });

        return $container;
    }
}
