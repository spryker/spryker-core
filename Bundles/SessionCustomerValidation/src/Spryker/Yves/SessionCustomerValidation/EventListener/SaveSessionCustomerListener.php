<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionCustomerValidation\EventListener;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SessionCustomerTransfer;
use Spryker\Yves\SessionCustomerValidation\Dependency\Client\SessionCustomerValidationToCustomerClientInterface;
use Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerSaverPluginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class SaveSessionCustomerListener implements EventSubscriberInterface
{
    /**
     * @var \Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerSaverPluginInterface
     */
    protected $sessionCustomerSaverPlugin;

    /**
     * @var \Spryker\Yves\SessionCustomerValidation\Dependency\Client\SessionCustomerValidationToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerSaverPluginInterface $sessionCustomerSaverPlugin
     * @param \Spryker\Yves\SessionCustomerValidation\Dependency\Client\SessionCustomerValidationToCustomerClientInterface $customerClient
     */
    public function __construct(
        SessionCustomerSaverPluginInterface $sessionCustomerSaverPlugin,
        SessionCustomerValidationToCustomerClientInterface $customerClient
    ) {
        $this->sessionCustomerSaverPlugin = $sessionCustomerSaverPlugin;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Symfony\Component\Security\Http\Event\InteractiveLoginEvent $event
     *
     * @return void
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->hasSession()) {
            return;
        }

        $user = $event->getAuthenticationToken()->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $customerTransfer = $this->customerClient->getCustomerByEmail(
            (new CustomerTransfer())->setEmail($user->getUsername()),
        );

        if (!$customerTransfer->getIdCustomer()) {
            return;
        }

        $this->sessionCustomerSaverPlugin->saveSessionCustomer(
            (new SessionCustomerTransfer())
                ->setIdCustomer($customerTransfer->getIdCustomerOrFail())
                ->setIdSession($request->getSession()->getId()),
        );
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }
}
