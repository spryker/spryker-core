<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SspDashboardManagement\Zed;

use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class SspDashboardManagementStub implements SspDashboardManagementStubInterface
{
    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequestClient
     */
    public function __construct(protected ZedRequestClientInterface $zedRequestClient)
    {
    }

    /**
     * @uses \SprykerFeature\Zed\SspDashboardManagement\Communication\Controller\GatewayController::getDashboardAction
     *
     * @param \Generated\Shared\Transfer\DashboardRequestTransfer $dashboardRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DashboardResponseTransfer
     */
    public function getDashboard(DashboardRequestTransfer $dashboardRequestTransfer): DashboardResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\DashboardResponseTransfer $dashboardResponseTransfer */
        $dashboardResponseTransfer = $this->zedRequestClient->call('/ssp-dashboard-management/gateway/get-dashboard', $dashboardRequestTransfer);

        return $dashboardResponseTransfer;
    }
}
