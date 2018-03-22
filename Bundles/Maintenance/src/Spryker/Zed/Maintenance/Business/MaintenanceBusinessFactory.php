<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Maintenance\Business\Model\Maintenance\MaintenanceMarkerFile;

/**
 * @method \Spryker\Zed\Maintenance\MaintenanceConfig getConfig()
 */
class MaintenanceBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Maintenance\Business\Model\Maintenance\MaintenanceInterface
     */
    public function createMaintenanceForZed()
    {
        return new MaintenanceMarkerFile($this->getConfig()->getMaintenanceMarkerDirZed());
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\Model\Maintenance\MaintenanceInterface
     */
    public function createMaintenanceForYves()
    {
        return new MaintenanceMarkerFile($this->getConfig()->getMaintenanceMarkerDirYves());
    }
}
