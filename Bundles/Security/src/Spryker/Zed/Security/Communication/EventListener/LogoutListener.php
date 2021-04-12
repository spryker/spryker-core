<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\HttpUtils;

class LogoutListener implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\Security\Http\HttpUtils
     */
    protected $httpUtils;

    /**
     * @var string
     */
    protected $targetUrl;

    /**
     * @var int
     */
    public static $priority;

    /**
     * @param \Symfony\Component\Security\Http\HttpUtils $httpUtils
     * @param string $targetUrl
     * @param int $priority
     */
    public function __construct(HttpUtils $httpUtils, string $targetUrl = '/', int $priority = 64)
    {
        $this->httpUtils = $httpUtils;
        $this->targetUrl = $targetUrl;

        static::$priority = $priority;
    }

    /**
     * @param \Symfony\Component\Security\Http\Event\LogoutEvent $event
     *
     * @return void
     */
    public function onLogout(LogoutEvent $event): void
    {
        if ($event->getResponse() !== null) {
            return;
        }

        $event->setResponse($this->httpUtils->createRedirectResponse($event->getRequest(), $this->targetUrl));
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => ['onLogout', static::$priority],
        ];
    }
}
