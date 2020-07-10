<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DashboardMerchantPortalGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DashboardCardTransfer;

/**
 * Use this plugin when a new merchant dashboard card needs to be added.
 */
interface DashboardCardPluginInterface
{
    /**
     * Specification:
     * - Returns data for displaying a dashboard card.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DashboardCardTransfer
     */
    public function getDashboardCard(): DashboardCardTransfer;
}
