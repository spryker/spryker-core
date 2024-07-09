<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Communication\Plugin\Security\Handler;

use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * @method \Spryker\Zed\SecurityGui\Communication\SecurityGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityGui\Business\SecurityGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\SecurityGui\SecurityGuiConfig getConfig()
 */
class UserAuthenticationFailureHandler extends AbstractPlugin implements AuthenticationFailureHandlerInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_AUTHENTICATION_FAILED = 'Authentication failed!';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
    {
        $this->getFactory()
            ->getMessengerFacade()
            ->addErrorMessage(
                (new MessageTransfer())
                    ->setValue(static::MESSAGE_AUTHENTICATION_FAILED),
            );

        $this->getFactory()->createAuditLogger()->addFailedLoginAuditLog();

        return new RedirectResponse($this->getConfig()->getUrlLogin());
    }
}
