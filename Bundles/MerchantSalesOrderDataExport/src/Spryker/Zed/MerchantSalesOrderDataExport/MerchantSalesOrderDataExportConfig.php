<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantSalesOrderDataExportConfig extends AbstractBundleConfig
{
    protected const MODULE_ROOT_DIRECTORY_LEVEL = 4;

    /**
     * Specification:
     * - Returns the fully qualified file path that contains the module data export configurations.
     *
     * @api
     *
     * @return string
     */
    public function getModuleDataExportConfigurationsFilePath(): string
    {
        return $this->getModuleDataExportConfigurationPath() . 'merchant_sales_export_config.yml';
    }

    /**
     * @return string
     */
    protected function getModuleDataExportConfigurationPath(): string
    {
        $moduleRoot = realpath(
            dirname(__DIR__, static::MODULE_ROOT_DIRECTORY_LEVEL)
        );

        return $moduleRoot . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
    }
}
