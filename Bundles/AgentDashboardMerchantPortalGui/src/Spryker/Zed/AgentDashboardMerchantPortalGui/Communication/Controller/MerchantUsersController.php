<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\AgentDashboardMerchantPortalGuiCommunicationFactory getFactory()
 */
class MerchantUsersController extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        return $this->viewResponse([
            'merchantUserTableConfiguration' => $this->getFactory()
                ->createMerchantUserGuiTableConfigurationProvider()
                ->getConfiguration(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableDataAction(Request $request): Response
    {
        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createMerchantUserGuiTableDataProvider(),
            $this->getFactory()->createMerchantUserGuiTableConfigurationProvider()->getConfiguration(),
        );
    }
}
