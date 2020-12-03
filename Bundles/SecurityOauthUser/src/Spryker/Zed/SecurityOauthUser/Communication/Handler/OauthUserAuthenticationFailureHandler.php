<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Communication\Handler;

use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToMessengerFacadeInterface;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class OauthUserAuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    /**
     * @var \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig
     */
    protected $securityOauthUserConfig;

    /**
     * @param \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToMessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig $securityOauthUserConfig
     */
    public function __construct(
        SecurityOauthUserToMessengerFacadeInterface $messengerFacade,
        SecurityOauthUserConfig $securityOauthUserConfig
    ) {
        $this->messengerFacade = $messengerFacade;
        $this->securityOauthUserConfig = $securityOauthUserConfig;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->messengerFacade->addErrorMessage(
            (new MessageTransfer())->setValue('Authentication failed!')
        );

        return new RedirectResponse($this->securityOauthUserConfig->getUrlLogin());
    }
}
