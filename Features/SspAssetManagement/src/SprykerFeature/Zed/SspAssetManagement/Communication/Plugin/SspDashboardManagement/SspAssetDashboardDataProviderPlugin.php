<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Communication\Plugin\SspDashboardManagement;

use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\SspDashboardManagement\Dependency\Plugin\DashboardDataProviderPluginInterface;

/**
 * @method \SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspAssetManagement\Communication\SspAssetManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementBusinessFactory getBusinessFactory()
 */
class SspAssetDashboardDataProviderPlugin extends AbstractPlugin implements DashboardDataProviderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DashboardResponseTransfer $dashboardResponseTransfer
     * @param \Generated\Shared\Transfer\DashboardRequestTransfer $dashboardRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DashboardResponseTransfer
     */
    public function provideDashboardData(
        DashboardResponseTransfer $dashboardResponseTransfer,
        DashboardRequestTransfer $dashboardRequestTransfer
    ): DashboardResponseTransfer {
        return $this->getBusinessFactory()
            ->createDashboardDataProvider()
            ->provideDashboardData($dashboardResponseTransfer, $dashboardRequestTransfer);
    }
}
