<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class DataExportConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getExportConfigurationDefaultsPath(): string
    {
        // Bundles/DataExport/data/export/config/defaults_config.yml
        return realpath(sprintf('%s/../../../../data/export/config/defaults_config.yml', __DIR__));
    }

    /**
     * @return string
     */
    public function getExportConfigurationsPath(): string
    {
        // /data/export/config
        return realpath(sprintf('%s/data/export/config', APPLICATION_ROOT_DIR));
    }
}
