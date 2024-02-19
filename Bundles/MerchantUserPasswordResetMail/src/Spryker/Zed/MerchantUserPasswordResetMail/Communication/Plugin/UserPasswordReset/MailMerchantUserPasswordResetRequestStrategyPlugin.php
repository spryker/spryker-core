<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserPasswordResetMail\Communication\Plugin\UserPasswordReset;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\UserPasswordResetRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserPasswordResetExtension\Dependency\Plugin\UserPasswordResetRequestStrategyPluginInterface;

/**
 * @method \Spryker\Zed\MerchantUserPasswordResetMail\MerchantUserPasswordResetMailConfig getConfig()
 * @method \Spryker\Zed\MerchantUserPasswordResetMail\Communication\MerchantUserPasswordResetMailCommunicationFactory getFactory()
 */
class MailMerchantUserPasswordResetRequestStrategyPlugin extends AbstractPlugin implements UserPasswordResetRequestStrategyPluginInterface
{
    /**
     * @uses {@link Spryker\Zed\MerchantUserPasswordResetMail\Communication\Plugin\MailMerchantUserPasswordResetMailTypeBuilderPlugin::MAIL_TYPE}
     *
     * @var string
     */
    protected const MAIL_TYPE = 'merchant restore password';

    /**
     * {@inheritDoc}
     * - Returns true if merchant user exists for `UserPasswordResetRequest.user`, false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer): bool
    {
        /** @var \Generated\Shared\Transfer\UserTransfer $userTransfer */
        $userTransfer = $userPasswordResetRequestTransfer->getUser();

        return (bool)$this->getFactory()
            ->getMerchantUserFacade()
            ->findMerchantUser(
                (new MerchantUserCriteriaTransfer())->setIdUser(
                    $userTransfer->getIdUser(),
                ),
            );
    }

    /**
     * {@inheritDoc}
     * - Sends merchant user reset password email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer
     *
     * @return void
     */
    public function handleUserPasswordResetRequest(UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer): void
    {
        $this->getFactory()
            ->getMailFacade()
            ->handleMail(
                (new MailTransfer())
                    ->fromArray($userPasswordResetRequestTransfer->toArray(), true)
                    ->setStoreName($this->getFactory()
                        ->getStoreFacade()
                        ->getCurrentStore(true)
                        ->getNameOrFail())
                    ->setType(static::MAIL_TYPE),
            );
    }
}
