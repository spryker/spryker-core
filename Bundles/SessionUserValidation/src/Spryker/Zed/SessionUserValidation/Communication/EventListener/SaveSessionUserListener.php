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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class SaveSessionUserListener implements EventSubscriberInterface
{
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
        $userCriteriaTransfer = $this->createUserCriteriaTransfer($user->getUsername());
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
}
