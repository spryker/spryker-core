<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelLogListener implements EventSubscriberInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return void
     */
    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->logRequest($event->getRequest());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function logRequest(Request $request)
    {
        $message = sprintf(
            '%s Request %s [%s] %s',
            $this->getSapi(),
            APPLICATION,
            $request->getMethod(),
            $request->getRequestUri()
        );

        $this->logger->info($message, ['request' => $request]);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
     *
     * @return void
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->logResponse($event->getResponse());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return void
     */
    protected function logResponse(Response $response)
    {
        if ($response instanceof RedirectResponse) {
            $message = sprintf(
                '%s Response %s [%s], target URL %s',
                $this->getSapi(),
                APPLICATION,
                $response->getStatusCode(),
                $response->getTargetUrl()
            );
        } else {
            $message = sprintf(
                '%s Response %s [%s]',
                $this->getSapi(),
                APPLICATION,
                $response->getStatusCode()
            );
        }

        $this->logger->info($message, ['response' => $response]);
    }

    /**
     * @return string
     */
    protected function getSapi()
    {
        return (PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg') ? 'CLI' : 'WEB';
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest'],
            KernelEvents::RESPONSE => ['onKernelResponse'],
        ];
    }
}
