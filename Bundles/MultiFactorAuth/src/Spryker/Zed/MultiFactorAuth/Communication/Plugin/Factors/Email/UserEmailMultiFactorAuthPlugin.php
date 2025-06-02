<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Plugin\Factors\Email;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()()
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class UserEmailMultiFactorAuthPlugin extends AbstractPlugin implements MultiFactorAuthPluginInterface
{
    /**
     * @var string
     */
    protected const EMAIL_MULTI_FACTOR_AUTH_METHOD = 'email';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::EMAIL_MULTI_FACTOR_AUTH_METHOD;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $multiFactorAuthMethod
     *
     * @return bool
     */
    public function isApplicable(string $multiFactorAuthMethod): bool
    {
        return $multiFactorAuthMethod === static::EMAIL_MULTI_FACTOR_AUTH_METHOD;
    }

    /**
     * {@inheritDoc}
     * - Returns an empty string as the additional configuration for the email multi-factor authentication method is not required.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return string
     */
    public function getConfiguration(MultiFactorAuthTransfer $multiFactorAuthTransfer): string
    {
        return '';
    }

    /**
     * {@inheritDoc}
     * - Generates the multi-factor authentication code for the user.
     * - Sends the multi-factor authentication code to the user's email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    public function sendCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): void
    {
        $this->getFacade()->sendUserCode($multiFactorAuthTransfer);
    }
}
