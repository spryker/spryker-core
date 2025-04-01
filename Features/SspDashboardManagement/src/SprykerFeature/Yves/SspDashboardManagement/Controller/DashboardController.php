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
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @method \SprykerFeature\Yves\SspDashboardManagement\SspDashboardManagementFactory getFactory()
 */
class DashboardController extends AbstractController
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const ROUTE_LOGIN = 'login';

    /**
     * @var string
     */
    public const MESSAGE_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): View|RedirectResponse
    {
        if (
            !$this->can(
                ViewDashboardPermissionPlugin::KEY,
                $this->getFactory()->getCustomerClient()->getCustomer()?->getCompanyUserTransferOrFail()->getIdCompanyUserOrFail(),
            )
        ) {
            throw new AccessDeniedHttpException('dashboard.access.denied');
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
