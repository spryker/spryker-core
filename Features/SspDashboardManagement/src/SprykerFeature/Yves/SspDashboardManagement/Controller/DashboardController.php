<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspDashboardManagement\Controller;

use Generated\Shared\Transfer\DashboardRequestTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\View\View;
use SprykerFeature\Shared\SspDashboardManagement\Plugin\Permission\ViewDashboardPermissionPlugin;
use SprykerFeature\Yves\SspDashboardManagement\Exception\SspDashboardManagementAccessDeniedHttpException;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Yves\SspDashboardManagement\SspDashboardManagementFactory getFactory()
 */
class DashboardController extends AbstractController
{
    use PermissionAwareTrait;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \SprykerFeature\Yves\SspDashboardManagement\Exception\SspDashboardManagementAccessDeniedHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        if (!$this->can(ViewDashboardPermissionPlugin::KEY)) {
            throw new SspDashboardManagementAccessDeniedHttpException();
        }

        $viewData = $this->executeIndexAction();

        return $this->view(
            $viewData,
            [],
            '@SspDashboardManagement/views/dashboard/dashboard.twig',
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function executeIndexAction(): array
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        $dashboardRequestTransfer = (new DashboardRequestTransfer())
            ->setStore($this->getFactory()->getStoreClient()->getCurrentStore())
            ->setCompanyUser($customerTransfer?->getCompanyUserTransfer());

        $dashboardResponseTransfer = $this
            ->getFactory()
            ->getSspDashboardManagementClient()
            ->getDashboard($dashboardRequestTransfer);

        return [
            'dashboard' => $dashboardResponseTransfer,
            'customer' => $customerTransfer,
        ];
    }
}
