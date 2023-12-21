<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Plugin\Security\Listener;

use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\User\Business\UserFacadeInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
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
    public function authenticate(RequestEvent $event): void
    {
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return;
        }

        if (!$token->getUser() instanceof UserInterface) {
            return;
        }

        $currentUser = $this->userFacade->getCurrentUser();
        if ($currentUser->getUsername() === $this->getUserIdentifier($token->getUser())) {
            return;
        }

        $currentUser = $this->getUserTransfer(
            $this->getUserIdentifier($token->getUser()),
        );

        $this->userFacade->setCurrentUser($currentUser);

        $event->setResponse(
            new RedirectResponse($event->getRequest()->getPathInfo()),
        );
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function getUserTransfer(string $username): UserTransfer
    {
        $userCriteriaTransfer = $this->createUserCriteriaTransfer($username);
        $userCollectionTransfer = $this->userFacade->getUserCollection($userCriteriaTransfer);

        return $userCollectionTransfer->getUsers()->getIterator()->current();
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserCriteriaTransfer
     */
    protected function createUserCriteriaTransfer(string $username): UserCriteriaTransfer
    {
        $userConditionsTransfer = (new UserConditionsTransfer())
            ->addUsername($username)
            ->setThrowUserNotFoundException(true);

        return (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return string
     */
    protected function getUserIdentifier(UserInterface $user): string
    {
        if ($this->isSymfonyVersion5() === true) {
            return $user->getUsername();
        }

        return $user->getUserIdentifier();
    }

    /**
     * @deprecated Shim for Symfony Security Core 5.x, to be removed when Symfony Security Core dependency becomes 6.x+.
     *
     * @return bool
     */
    protected function isSymfonyVersion5(): bool
    {
        return class_exists(AuthenticationProviderManager::class);
    }
}
