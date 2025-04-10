<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Strategy\Customer;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Spryker\Zed\MultiFactorAuth\Business\Strategy\SendStrategyInterface;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMailFacadeInterface;

class CustomerEmailCodeSenderStrategy implements SendStrategyInterface
{
    /**
     * @uses \Spryker\Zed\MultiFactorAuth\Business\Plugin\Mail\Customer\CustomerEmailMultiFactorAuthMailTypeBuilderPlugin::MAIL_TYPE
     *
     * @var string
     */
    protected const MAIL_TYPE = 'CUSTOMER_EMAIL_MULTI_FACTOR_AUTH_MAIL';

    /**
     * @var string
     */
    protected const EMAIL_MULTI_FACTOR_AUTH_METHOD = 'email';

    /**
     * @param \Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMailFacadeInterface $mailFacade
     */
    public function __construct(protected MultiFactorAuthToMailFacadeInterface $mailFacade)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return bool
     */
    public function isApplicable(MultiFactorAuthTransfer $multiFactorAuthTransfer): bool
    {
        return $multiFactorAuthTransfer->getType() === static::EMAIL_MULTI_FACTOR_AUTH_METHOD;
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function send(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer
    {
        $mailTransfer = (new MailTransfer())
            ->setType(static::MAIL_TYPE)
            ->setCustomer($multiFactorAuthTransfer->getCustomer())
            ->setMultiFactorAuth($multiFactorAuthTransfer);

        $this->mailFacade->handleMail($mailTransfer);

        return $multiFactorAuthTransfer;
    }
}
