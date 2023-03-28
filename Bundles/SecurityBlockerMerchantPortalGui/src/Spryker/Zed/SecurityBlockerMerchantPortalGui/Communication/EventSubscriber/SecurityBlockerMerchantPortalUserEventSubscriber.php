<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\EventSubscriber;

use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\Builder\MessageBuilderInterface;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\Dependency\Client\SecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\SecurityBlockerMerchantPortalGuiConfig;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\AuthenticationEvents;

class SecurityBlockerMerchantPortalUserEventSubscriber implements EventSubscriberInterface
{
    /**
     * @uses \Spryker\Zed\SecurityMerchantPortalGui\Communication\Form\MerchantLoginForm::FORM_NAME
     *
     * @var string
     */
    protected const FORM_NAME = 'security-merchant-portal-gui';

    /**
     * @uses \Spryker\Zed\SecurityMerchantPortalGui\Communication\Form\MerchantLoginForm::FIELD_USERNAME
     *
     * @var string
     */
    protected const FIELD_USERNAME = 'username';

    /**
     * @var string
     */
    protected const ROUTE_KEY = '_route';

    /**
     * @var int
     */
    protected const KERNEL_REQUEST_SUBSCRIBER_PRIORITY = 9;

    /**
     * @var string
     */
    protected const AUTHENTICATION_FAILURE_EVENT = 'onAuthenticationFailure';

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected RequestStack $requestStack;

    /**
     * @var \Spryker\Zed\SecurityBlockerMerchantPortalGui\Dependency\Client\SecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface
     */
    protected SecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface $securityBlockerClient;

    /**
     * @var \Spryker\Zed\SecurityBlockerMerchantPortalGui\SecurityBlockerMerchantPortalGuiConfig
     */
    protected SecurityBlockerMerchantPortalGuiConfig $securityBlockerMerchantPortalGuiConfig;

    /**
     * @var \Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\Builder\MessageBuilderInterface
     */
    protected MessageBuilderInterface $messageBuilder;

    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \Spryker\Zed\SecurityBlockerMerchantPortalGui\Dependency\Client\SecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface $securityBlockerClient
     * @param \Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\Builder\MessageBuilderInterface $messageBuilder
     * @param \Spryker\Zed\SecurityBlockerMerchantPortalGui\SecurityBlockerMerchantPortalGuiConfig $securityBlockerMerchantPortalGuiConfig
     */
    public function __construct(
        RequestStack $requestStack,
        SecurityBlockerMerchantPortalGuiToSecurityBlockerClientInterface $securityBlockerClient,
        MessageBuilderInterface $messageBuilder,
        SecurityBlockerMerchantPortalGuiConfig $securityBlockerMerchantPortalGuiConfig
    ) {
        $this->requestStack = $requestStack;
        $this->securityBlockerClient = $securityBlockerClient;
        $this->messageBuilder = $messageBuilder;
        $this->securityBlockerMerchantPortalGuiConfig = $securityBlockerMerchantPortalGuiConfig;
    }

    /**
     * @return array<string, array<int, int|string>|string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationEvents::AUTHENTICATION_FAILURE => static::AUTHENTICATION_FAILURE_EVENT,
            KernelEvents::REQUEST => ['onKernelRequest', static::KERNEL_REQUEST_SUBSCRIBER_PRIORITY],
        ];
    }

    /**
     * @return void
     */
    public function onAuthenticationFailure(): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request || !$this->isLoginAttempt($request)) {
            return;
        }

        $securityCheckAuthContextTransfer = $this->createSecurityCheckAuthContextTransfer($request);
        $this->securityBlockerClient->incrementLoginAttemptCount($securityCheckAuthContextTransfer);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->isLoginAttempt($request)) {
            return;
        }

        $securityCheckAuthContextTransfer = $this->createSecurityCheckAuthContextTransfer($request);
        $securityCheckAuthResponseTransfer = $this->securityBlockerClient->isAccountBlocked($securityCheckAuthContextTransfer);

        if (!$securityCheckAuthResponseTransfer->getIsBlocked()) {
            return;
        }

        $exceptionMessage = $this->messageBuilder->getExceptionMessage($securityCheckAuthResponseTransfer);

        throw new HttpException(
            Response::HTTP_TOO_MANY_REQUESTS,
            $exceptionMessage,
            null,
            [],
            Response::HTTP_TOO_MANY_REQUESTS,
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isLoginAttempt(Request $request): bool
    {
        $currentRoute = $request->attributes->get(static::ROUTE_KEY);
        $configuredRoute = $this->securityBlockerMerchantPortalGuiConfig->getMerchantPortalUserLoginCheckUrl();

        return $currentRoute === $configuredRoute
            && $request->getMethod() === Request::METHOD_POST;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer
     */
    protected function createSecurityCheckAuthContextTransfer(Request $request): SecurityCheckAuthContextTransfer
    {
        return (new SecurityCheckAuthContextTransfer())
            ->setType($this->securityBlockerMerchantPortalGuiConfig->getSecurityBlockerMerchantPortalUserEntityType())
            ->setIp($request->getClientIp());
    }
}
