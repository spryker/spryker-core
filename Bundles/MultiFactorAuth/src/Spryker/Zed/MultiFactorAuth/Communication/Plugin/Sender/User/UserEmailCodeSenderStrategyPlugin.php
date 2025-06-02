<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Plugin\Sender\User;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\SendStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class UserEmailCodeSenderStrategyPlugin extends AbstractPlugin implements SendStrategyPluginInterface
{
    /**
     * @uses \Spryker\Zed\MultiFactorAuth\Communication\Plugin\Mail\User\UserEmailMultiFactorAuthMailTypeBuilderPlugin::MAIL_TYPE
     *
     * @var string
     */
    protected const MAIL_TYPE = 'USER_EMAIL_MULTI_FACTOR_AUTH_MAIL';

    /**
     * @var string
     */
    protected const EMAIL_MULTI_FACTOR_AUTH_METHOD = 'email';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return bool
     */
    public function isApplicable(MultiFactorAuthTransfer $multiFactorAuthTransfer): bool
    {
        return $multiFactorAuthTransfer->getType() === static::EMAIL_MULTI_FACTOR_AUTH_METHOD;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function send(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer
    {
        $mailTransfer = (new MailTransfer())
            ->setType(static::MAIL_TYPE)
            ->setUser($multiFactorAuthTransfer->getUser())
            ->setMultiFactorAuth($multiFactorAuthTransfer);

        $this->getFactory()->getMailFacade()->handleMail($mailTransfer);

        return $multiFactorAuthTransfer;
    }
}
