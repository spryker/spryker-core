<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Dashboard\Handler;

use Spryker\Client\Customer\CustomerClientInterface;
use SprykerFeature\Yves\SelfServicePortal\Controller\DashboardController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\RouterInterface;

class SspDashboardRestrictionHandler implements SspDashboardRestrictionHandlerInterface
{
    /**
     * @var array<int, string>
     */
    protected const DASHBOARD_CONTROLLERS = [
        DashboardController::class,
    ];

    public function __construct(
        protected CustomerClientInterface $customerClient,
        protected RouterInterface $router
    ) {
    }

    public function handleRestriction(ControllerEvent $event): void
    {
        $eventController = $event->getController();
        if (!is_array($eventController)) {
            return;
        }

        [$controllerInstance] = $eventController;

        if (!in_array(get_class($controllerInstance), static::DASHBOARD_CONTROLLERS, true)) {
            return;
        }

        $customerTransfer = $this->customerClient->getCustomer();

        if ($customerTransfer && ($customerTransfer->getCompanyUserTransfer() || $customerTransfer->getIsOnBehalf())) {
            return;
        }

        $loginRoute = $this->router->generate('login');
        $event->setController(function () use ($loginRoute): RedirectResponse {
            return new RedirectResponse($loginRoute);
        });
    }
}
