<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance;

use Spryker\Shared\Maintenance\MaintenanceConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MaintenanceConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getMaintenancePageZed()
    {
        return $this->get(MaintenanceConstants::MAINTENANCE_PAGE_ZED);
    }

    /**
     * @return string
     */
    public function getMaintenanceMarkerDirZed()
    {
        return APPLICATION_ROOT_DIR . '/public/Zed/maintenance';
    }

    /**
     * @return string
     */
    public function getMaintenancePageYves()
    {
        return $this->get(MaintenanceConstants::MAINTENANCE_PAGE_YVES);
    }

    /**
     * @return string
     */
    public function getMaintenanceMarkerDirYves()
    {
        return APPLICATION_ROOT_DIR . '/public/Yves/maintenance';
    }
}
