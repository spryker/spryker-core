<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionUserValidation\Communication\FirewallListener;

use Generated\Shared\Transfer\SessionUserTransfer;
use Spryker\Zed\SessionUserValidation\Dependency\Facade\SessionUserValidationToUserFacadeInterface;
use Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserValidatorPluginInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Firewall\AbstractListener;

class ValidateSessionUserListener extends AbstractListener
{
    /**
     * @uses \Spryker\Zed\SecurityGui\SecurityGuiConfig::LOGIN_PATH
     *
     * @var string
     */
    protected const LOGIN_PATH = '/security-gui/login';

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var \Spryker\Zed\SessionUserValidation\Dependency\Facade\SessionUserValidationToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserValidatorPluginInterface
     */
    protected $sessionUserValidatorPlugin;

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \Spryker\Zed\SessionUserValidation\Dependency\Facade\SessionUserValidationToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserValidatorPluginInterface $sessionUserValidatorPlugin
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        SessionUserValidationToUserFacadeInterface $userFacade,
        SessionUserValidatorPluginInterface $sessionUserValidatorPlugin
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->userFacade = $userFacade;
        $this->sessionUserValidatorPlugin = $sessionUserValidatorPlugin;
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

        if (!$this->userFacade->hasCurrentUser()) {
            return;
        }

        $currentUser = $this->userFacade->getCurrentUser();

        $session = $event->getRequest()->getSession();
        $sessionUserTransfer = (new SessionUserTransfer())
            ->setIdUser($currentUser->getIdUserOrFail())
            ->setIdSession($session->getId());

        if (!$this->sessionUserValidatorPlugin->isSessionUserValid($sessionUserTransfer)) {
            $session->invalidate(0);
            $this->tokenStorage->setToken(null);
            $event->setResponse(new RedirectResponse(static::LOGIN_PATH));
        }
    }
}
