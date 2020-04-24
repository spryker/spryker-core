<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesDataExportConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getDefaultOrderExportConfigurationPath(): string
    {
        // Bundles/SalesDataExport/data/export/config/order_export_config.yml
        return realpath(sprintf('%s/../../../../data/export/config/order_export_config.yml', __DIR__));
    }
}
