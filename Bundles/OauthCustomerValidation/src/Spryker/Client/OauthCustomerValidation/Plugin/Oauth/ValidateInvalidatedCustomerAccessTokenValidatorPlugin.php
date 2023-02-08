<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCustomerValidation\Plugin\Oauth;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\OauthExtension\Dependency\Plugin\AccessTokenValidatorPluginInterface;

/**
 * @method \Spryker\Client\OauthCustomerValidation\OauthCustomerValidationClientInterface getClient()
 */
class ValidateInvalidatedCustomerAccessTokenValidatorPlugin extends AbstractPlugin implements AccessTokenValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates provided access token if the customer is not anonymized and the password hasn't been changed after a token creation.
     * - Returns `OauthAccessTokenValidationResponseTransfer` with an error in case of failure.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $oauthAccessTokenValidationRequestTransfer
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer $oauthAccessTokenValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    public function validate(
        OauthAccessTokenValidationRequestTransfer $oauthAccessTokenValidationRequestTransfer,
        OauthAccessTokenValidationResponseTransfer $oauthAccessTokenValidationResponseTransfer
    ): OauthAccessTokenValidationResponseTransfer {
        return $this->getClient()->validateInvalidatedCustomerAccessToken(
            $oauthAccessTokenValidationRequestTransfer,
            $oauthAccessTokenValidationResponseTransfer,
        );
    }
}
