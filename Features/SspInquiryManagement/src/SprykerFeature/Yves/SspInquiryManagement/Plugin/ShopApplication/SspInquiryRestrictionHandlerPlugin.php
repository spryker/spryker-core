<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Plugin\ShopApplication;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\SspInquiryManagement\Controller\SspInquiryController;
use SprykerFeature\Yves\SspInquiryManagement\Controller\SspInquiryFileController;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\FilterControllerEventHandlerPluginInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

/**
 * @method \SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementFactory getFactory()
 */
class SspInquiryRestrictionHandlerPlugin extends AbstractPlugin implements FilterControllerEventHandlerPluginInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMPANY_PAGE_RESTRICTED = 'company_page.company_user_restricted_message';

    /**
     * @var array<int, string>
     */
    protected const SSP_INQUIRY_CONTROLLERS = [
        SspInquiryController::class,
        SspInquiryFileController::class,
    ];

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $event
     *
     * @return void
     */
    public function handle(ControllerEvent $event): void
    {
        $eventController = $event->getController();
        if (!is_array($eventController)) {
            return;
        }

        [$controllerInstance, $actionName] = $eventController;

        if (!in_array(get_class($controllerInstance), static::SSP_INQUIRY_CONTROLLERS, true)) {
            return;
        }

        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer && ($customerTransfer->getCompanyUserTransfer() || $customerTransfer->getIsOnBehalf())) {
            return;
        }

        $loginRoute = $this->getFactory()->getRouter()->generate('login');
        $event->setController(function () use ($loginRoute): RedirectResponse {
            return new RedirectResponse($loginRoute);
        });
    }
}
