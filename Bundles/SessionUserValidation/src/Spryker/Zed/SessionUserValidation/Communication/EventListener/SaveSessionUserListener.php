<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionUserValidation\Communication\EventListener;

use Generated\Shared\Transfer\SessionUserTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SessionUserValidation\Dependency\Facade\SessionUserValidationToUserFacadeInterface;
use Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserSaverPluginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class SaveSessionUserListener implements EventSubscriberInterface
{
    /**
     * @uses \Spryker\Zed\SecuritySystemUser\SecuritySystemUserConfig::ROLE_SYSTEM_USER
     *
     * @var string
     */
    protected const ROLE_SYSTEM_USER = 'ROLE_SYSTEM_USER';

    /**
     * @var \Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserSaverPluginInterface
     */
    protected $sessionUserSaverPlugin;

    /**
     * @var \Spryker\Zed\SessionUserValidation\Dependency\Facade\SessionUserValidationToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserSaverPluginInterface $sessionUserSaverPlugin
     * @param \Spryker\Zed\SessionUserValidation\Dependency\Facade\SessionUserValidationToUserFacadeInterface $userFacade
     */
    public function __construct(
        SessionUserSaverPluginInterface $sessionUserSaverPlugin,
        SessionUserValidationToUserFacadeInterface $userFacade
    ) {
        $this->sessionUserSaverPlugin = $sessionUserSaverPlugin;
        $this->userFacade = $userFacade;
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

        if (in_array(static::ROLE_SYSTEM_USER, $user->getRoles()) === true) {
            return;
        }

        $userTransfer = $this->getUserTransfer($user);

        $this->sessionUserSaverPlugin->saveSessionUser(
            (new SessionUserTransfer())
                ->setIdUser($userTransfer->getIdUserOrFail())
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

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function getUserTransfer(UserInterface $user): UserTransfer
    {
        $userCriteriaTransfer = $this->createUserCriteriaTransfer(
            $this->getUserIdentifier($user),
        );
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
