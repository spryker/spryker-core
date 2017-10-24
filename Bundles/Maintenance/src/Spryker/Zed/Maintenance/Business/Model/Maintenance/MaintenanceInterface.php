<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business\Model\Maintenance;

interface MaintenanceInterface
{
    /**
     * @return bool
     */
    public function isMaintenanceEnabled();

    /**
     * @return void
     */
    public function enableMaintenance();

    /**
     * @return void
     */
    public function disableMaintenance();
}
