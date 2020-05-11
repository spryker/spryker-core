<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesDataExportConfig extends AbstractBundleConfig
{
    protected const READ_BATCH_SIZE = 100;

    protected const WRITE_MODE_TYPE_OVERRIDE = 'wb';
    protected const WRITE_MODE_TYPE_APPEND = 'ab';

    protected const MODULE_ROOT_DIRECTORY_LEVEL = 4;

    /**
     * @return int
     */
    public function getReadBatchSize(): int
    {
        return static::READ_BATCH_SIZE;
    }

    /**
     * @return string
     */
    public function getWriteModeTypeOverride(): string
    {
        return static::WRITE_MODE_TYPE_OVERRIDE;
    }

    /**
     * @return string
     */
    public function getWriteModeTypeAppend(): string
    {
        return static::WRITE_MODE_TYPE_APPEND;
    }

    /**
     * @return string
     */
    public function getDefaultExportConfigurationPath(): string
    {
        return $this->getModuleDataExportDirectoryPath() . 'sales_export_config.yml';
    }

    /**
     * @return string
     */
    protected function getModuleDataExportDirectoryPath(): string
    {
        return $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    protected function getModuleRoot(): string
    {
        $moduleRoot = realpath(
            dirname(__DIR__, static::MODULE_ROOT_DIRECTORY_LEVEL)
        );

        return $moduleRoot . DIRECTORY_SEPARATOR;
    }
}
