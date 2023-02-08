<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;

/**
 * Validates provided OAuth access token.
 * The result can be used to log out an invalid customer.
 */
interface AccessTokenValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates provided access token.
     * - In case of error, returns an error message.
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
    ): OauthAccessTokenValidationResponseTransfer;
}
