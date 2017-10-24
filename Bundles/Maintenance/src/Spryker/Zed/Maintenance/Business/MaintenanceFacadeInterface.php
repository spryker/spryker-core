<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business;

interface MaintenanceFacadeInterface
{
    /**
     * Specification:
     * - Enables maintenance.
     *
     * @api
     *
     * @return void
     */
    public function enableMaintenance();

    /**
     * Specification:
     * - Disables maintenance.
     *
     * @api
     *
     * @return void
     */
    public function disableMaintenance();
}
