<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class DataExportConfig extends AbstractBundleConfig
{
    protected const MODULE_ROOT_DIRECTORY_LEVEL = 4;

    /**
     * Specification:
     * - Returns the path that contains the module independent configuration defaults.
     *
     * @api
     *
     * @return string
     */
    public function getExportConfigurationDefaultsPath(): string
    {
        return $this->getModuleExportConfigurationPath() . 'defaults_config.yml';
    }

    /**
     * Specification:
     * - Returns the path that contains the project specific data export configurations.
     *
     * @api
     *
     * @return string
     */
    public function getExportConfigurationsPath(): string
    {
        return realpath(
            APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . 'config'
        );
    }

    /**
     * @return string
     */
    protected function getModuleExportConfigurationPath(): string
    {
        $moduleRoot = realpath(
            dirname(__DIR__, static::MODULE_ROOT_DIRECTORY_LEVEL)
        );

        return $moduleRoot . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
    }
}
