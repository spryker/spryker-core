<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Subscriber;

use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Security\AgentMerchantUser;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\AgentSecurityMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig getConfig()
 */
class SwitchUserEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::SWITCH_USER => 'switchUser',
        ];
    }

    /**
     * @param \Symfony\Component\Security\Http\Event\SwitchUserEvent $switchUserEvent
     *
     * @return void
     */
    public function switchUser(SwitchUserEvent $switchUserEvent): void
    {
        $targetUser = $switchUserEvent->getTargetUser();
        $agentUsername = $this->findAgentUsername($switchUserEvent);

        if (is_a($targetUser, $this->getConfig()->getMerchantUserClassName(), true)) {
            $merchantUserTransfer = $targetUser->getMerchantUserTransfer()->setAgentUsername($agentUsername);

            $this->getFactory()->getMerchantUserFacade()->authenticateMerchantUser($merchantUserTransfer);
        }

        if ($targetUser instanceof AgentMerchantUser) {
            $this->getFactory()->getUserFacade()->setCurrentUser($targetUser->getUserTransfer());
        }
    }

    /**
     * @param \Symfony\Component\Security\Http\Event\SwitchUserEvent $switchUserEvent
     *
     * @return string|null
     */
    protected function findAgentUsername(SwitchUserEvent $switchUserEvent): ?string
    {
        $token = $switchUserEvent->getToken();
        if (!$token instanceof SwitchUserToken) {
            return null;
        }

        $originalUser = $token->getOriginalToken()->getUser();
        if (!$originalUser instanceof AgentMerchantUser) {
            return null;
        }

        return $originalUser->getUsername();
    }
}
