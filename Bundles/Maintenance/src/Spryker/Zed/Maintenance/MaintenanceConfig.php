<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MaintenanceConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Directory to which the marker file will be written.
     *
     * @api
     *
     * @return string
     */
    public function getMaintenanceMarkerDirZed()
    {
        return APPLICATION_ROOT_DIR . '/public/Zed/maintenance';
    }

    /**
     * Specification:
     * - Directory to which the marker file will be written.
     *
     * @api
     *
     * @return string
     */
    public function getMaintenanceMarkerDirYves()
    {
        return APPLICATION_ROOT_DIR . '/public/Yves/maintenance';
    }
}
