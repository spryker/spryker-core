<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\Subscriber;

use DateTime;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Session\SessionConstants;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class MigrateSessionEventSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            // low priority but higher than StreamedResponseListener
            KernelEvents::REQUEST => [['onKernelRequest']],
        ];
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     *
     * @return void
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $session = $event->getRequest()->getSession();
        if ($session && $session->isStarted() && $this->isNeedUpdate($session)) {
            $session->migrate(true);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     *
     * @return bool
     */
    protected function isNeedUpdate(SessionInterface $session): bool
    {
        $metadataBag = $session->getMetadataBag();
        $sessionCreated = $metadataBag->getCreated();
        $sessionLength = Config::get(SessionConstants::ZED_SESSION_COOKIE_TIME_TO_LIVE);
        $currentTimestamp = (new DateTime())->getTimestamp();

        return $currentTimestamp - $sessionCreated - $sessionLength <= SessionConstants::SESSION_UPDATE_TIMEOUT;
    }
}
