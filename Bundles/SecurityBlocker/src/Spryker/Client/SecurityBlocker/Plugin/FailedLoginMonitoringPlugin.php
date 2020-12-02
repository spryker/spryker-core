<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker\Plugin;

use Generated\Shared\Transfer\AuthContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;

/**
 * @method \Spryker\Client\SecurityBlocker\SecurityBlockerClientInterface getClient()
 * @method \Spryker\Client\SecurityBlocker\SecurityBlockerFactory getFactory()
 */
class FailedLoginMonitoringPlugin extends AbstractPlugin implements EventSubscriberInterface
{
    protected const FORM_FIELD_SELECTOR_EMAIL = 'loginForm[email]';

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'logAuthenticationFailure',
        ];
    }

    /**
     * @return void
     */
    protected function logAuthenticationFailure(): void
    {
        $request = $this->getFactory()->getRequestStack()->getCurrentRequest();

        $authContextTransfer = (new AuthContextTransfer)
            ->setTtl(0)
            ->setAccount($request->get(static::FORM_FIELD_SELECTOR_EMAIL))
            ->setIp($request->getClientIp());

        $this->getClient()->incrementLoginAttempt($authContextTransfer);
    }
}
