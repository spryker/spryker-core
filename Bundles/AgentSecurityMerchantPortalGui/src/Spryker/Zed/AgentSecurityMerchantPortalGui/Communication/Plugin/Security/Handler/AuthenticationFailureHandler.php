<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\Handler;

use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\AgentSecurityMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig getConfig()
 */
class AuthenticationFailureHandler extends AbstractPlugin implements AuthenticationFailureHandlerInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_AUTHENTICATION_FAILED = 'Authentication failed!';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $this->getFactory()
            ->getMessengerFacade()
            ->addErrorMessage(
                (new MessageTransfer())->setValue(static::MESSAGE_AUTHENTICATION_FAILED),
            );

        $this->getFactory()->createAuditLogger()->addAgentFailedLoginAuditLog();

        if ($this->getFactory()->getMerchantAgentUserMultiFactorAuthenticationHandlerPlugins() !== []) {
            return $this->createRedirectResponse();
        }

        return new RedirectResponse($this->getConfig()->getUrlLogin());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createRedirectResponse(): JsonResponse
    {
        return new JsonResponse($this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addActionRedirect($this->getConfig()->getUrlLogin())
            ->createResponse()
            ->toArray());
    }
}
