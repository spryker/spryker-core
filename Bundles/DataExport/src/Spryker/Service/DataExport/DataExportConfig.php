<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport;

use Spryker\Service\Kernel\AbstractBundleConfig;

class DataExportConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getDataExportDefaultLocalPath(): string
    {
        return realpath(sprintf('%s/data/export', APPLICATION_ROOT_DIR));
    }
}
