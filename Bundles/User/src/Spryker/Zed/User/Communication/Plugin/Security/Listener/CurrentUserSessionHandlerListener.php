<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Plugin\Security\Listener;

use Spryker\Zed\User\Business\UserFacadeInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Firewall\AbstractListener;

class CurrentUserSessionHandlerListener extends AbstractListener
{
    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \Spryker\Zed\User\Business\UserFacadeInterface $userFacade
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        UserFacadeInterface $userFacade
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->userFacade = $userFacade;
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
    public function authenticate(RequestEvent $event)
    {
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return;
        }

        if (!$token->getUser() instanceof UserInterface) {
            return;
        }

        $currentUser = $this->userFacade->getCurrentUser();
        if ($currentUser->getUsername() === $token->getUser()->getUsername()) {
            return;
        }

        $currentUser = $this->userFacade->getUserByUsername(
            $token->getUser()->getUsername()
        );

        $this->userFacade->setCurrentUser($currentUser);

        $event->setResponse(
            new RedirectResponse($event->getRequest()->getPathInfo())
        );
    }
}
