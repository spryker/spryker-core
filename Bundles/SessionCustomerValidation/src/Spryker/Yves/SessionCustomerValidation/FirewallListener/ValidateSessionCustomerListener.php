<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionCustomerValidation\FirewallListener;

use Generated\Shared\Transfer\SessionCustomerTransfer;
use Spryker\Yves\SessionCustomerValidation\Dependency\Client\SessionCustomerValidationToCustomerClientInterface;
use Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerValidatorPluginInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Firewall\AbstractListener;

class ValidateSessionCustomerListener extends AbstractListener
{
    /**
     * @var string
     */
    protected const LOGIN_PATH = '/login';

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var \Spryker\Yves\SessionCustomerValidation\Dependency\Client\SessionCustomerValidationToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerValidatorPluginInterface
     */
    protected $sessionCustomerValidatorPlugin;

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \Spryker\Yves\SessionCustomerValidation\Dependency\Client\SessionCustomerValidationToCustomerClientInterface $customerClient
     * @param \Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerValidatorPluginInterface $sessionCustomerValidatorPlugin
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        SessionCustomerValidationToCustomerClientInterface $customerClient,
        SessionCustomerValidatorPluginInterface $sessionCustomerValidatorPlugin
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->customerClient = $customerClient;
        $this->sessionCustomerValidatorPlugin = $sessionCustomerValidatorPlugin;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool|null
     */
    public function supports(Request $request): ?bool
    {
        return null;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return void
     */
    public function authenticate(RequestEvent $event): void
    {
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return;
        }

        if (
            !$token->getUser() instanceof UserInterface
            || !$event->getRequest()->hasSession()
        ) {
            return;
        }

        $currentCustomer = $this->customerClient->getCustomer();

        if ($currentCustomer === null) {
            return;
        }

        $session = $event->getRequest()->getSession();
        $sessionCustomerTransfer = (new SessionCustomerTransfer())
            ->setIdCustomer($currentCustomer->getIdCustomerOrFail())
            ->setIdSession($session->getId());

        if (!$this->sessionCustomerValidatorPlugin->isSessionCustomerValid($sessionCustomerTransfer)) {
            $this->customerClient->logout();
            $this->tokenStorage->setToken();

            $event->setResponse(new RedirectResponse(static::LOGIN_PATH));
        }
    }
}
