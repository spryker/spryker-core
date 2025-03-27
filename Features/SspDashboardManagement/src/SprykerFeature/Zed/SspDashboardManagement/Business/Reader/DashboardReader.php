<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspDashboardManagement\Business\Reader;

use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;

class DashboardReader implements DashboardReaderInterface
{
    /**
     * @param array<int, \SprykerFeature\Zed\SspDashboardManagement\Dependency\Plugin\DashboardDataProviderPluginInterface> $dashboardDataProviderPlugins
     */
    public function __construct(protected array $dashboardDataProviderPlugins)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\DashboardRequestTransfer $dashboardRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DashboardResponseTransfer
     */
    public function getDashboard(DashboardRequestTransfer $dashboardRequestTransfer): DashboardResponseTransfer
    {
        $dashboardResponseTransfer = new DashboardResponseTransfer();
        foreach ($this->dashboardDataProviderPlugins as $dashboardDataProviderPlugin) {
            $dashboardResponseTransfer = $dashboardDataProviderPlugin->provideDashboardData($dashboardResponseTransfer, $dashboardRequestTransfer);
        }

        return $dashboardResponseTransfer;
    }
}
