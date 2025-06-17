<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Inquiry\Handler;

use Spryker\Client\Customer\CustomerClientInterface;
use SprykerFeature\Yves\SelfServicePortal\Controller\DownloadInquiryFileController;
use SprykerFeature\Yves\SelfServicePortal\Controller\InquiryController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\RouterInterface;

class SspInquiryRestrictionHandler implements SspInquiryRestrictionHandlerInterface
{
    /**
     * @var array<int, string>
     */
    protected const SSP_INQUIRY_CONTROLLERS = [
        InquiryController::class,
        DownloadInquiryFileController::class,
    ];

    /**
     * @param \Spryker\Client\Customer\CustomerClientInterface $customerClient
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(
        protected CustomerClientInterface $customerClient,
        protected RouterInterface $router
    ) {
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $event
     *
     * @return void
     */
    public function handleRestriction(ControllerEvent $event): void
    {
        $eventController = $event->getController();
        if (!is_array($eventController)) {
            return;
        }

        [$controllerInstance, $actionName] = $eventController;

        if (!in_array(get_class($controllerInstance), static::SSP_INQUIRY_CONTROLLERS, true)) {
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
