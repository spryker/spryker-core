<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspDashboardManagement\Business;

use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerFeature\Zed\SspDashboardManagement\Business\SspDashboardManagementBusinessFactory getFactory()
 */
class SspDashboardManagementFacade extends AbstractFacade implements SspDashboardManagementFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DashboardRequestTransfer $dashboardRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DashboardResponseTransfer
     */
    public function getDashboard(DashboardRequestTransfer $dashboardRequestTransfer): DashboardResponseTransfer
    {
        return $this->getFactory()->createDashboardReader()->getDashboard($dashboardRequestTransfer);
    }
}
