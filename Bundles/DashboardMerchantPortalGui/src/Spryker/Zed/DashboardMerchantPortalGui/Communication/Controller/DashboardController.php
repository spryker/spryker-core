<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DashboardMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\DashboardMerchantPortalGui\Communication\DashboardMerchantPortalGuiCommunicationFactory getFactory()
 */
class DashboardController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $dashboardCardTransfers = [];
        foreach ($this->getFactory()->getDashboardCardPlugins() as $cardPlugin) {
            $dashboardCardTransfers[] = $cardPlugin->getDashboardCard();
        }

        return [
            'dashboardCards' => $dashboardCardTransfers,
        ];
    }
}
