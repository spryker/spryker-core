<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\DashboardDataExpander;

use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;

interface ServiceDashboardDataExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\DashboardResponseTransfer $dashboardResponseTransfer
     * @param \Generated\Shared\Transfer\DashboardRequestTransfer $dashboardRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DashboardResponseTransfer
     */
    public function provideSspServiceDashboardData(
        DashboardResponseTransfer $dashboardResponseTransfer,
        DashboardRequestTransfer $dashboardRequestTransfer
    ): DashboardResponseTransfer;
}
