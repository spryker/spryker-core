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
     * - Enables maintenance for Zed.
     *
     * @api
     *
     * @return void
     */
    public function enableMaintenanceForZed();

    /**
     * Specification:
     * - Disables maintenance for Zed.
     *
     * @api
     *
     * @return void
     */
    public function disableMaintenanceForZed();

    /**
     * Specification:
     * - Enables maintenance for Yves.
     *
     * @api
     *
     * @return void
     */
    public function enableMaintenanceForYves();

    /**
     * Specification:
     * - Disables maintenance for Zed.
     *
     * @api
     *
     * @return void
     */
    public function disableMaintenanceForYves();
}
