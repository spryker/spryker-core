<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCustomerValidation;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\OauthCustomerValidation\OauthCustomerValidationFactory getFactory()
 */
class OauthCustomerValidationClient extends AbstractClient implements OauthCustomerValidationClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $oauthAccessTokenValidationRequestTransfer
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer $oauthAccessTokenValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    public function validateInvalidatedCustomerAccessToken(
        OauthAccessTokenValidationRequestTransfer $oauthAccessTokenValidationRequestTransfer,
        OauthAccessTokenValidationResponseTransfer $oauthAccessTokenValidationResponseTransfer
    ): OauthAccessTokenValidationResponseTransfer {
        return $this->getFactory()->createInvalidatedCustomerAccessTokenValidator()
            ->validateInvalidatedCustomerAccessToken(
                $oauthAccessTokenValidationRequestTransfer,
                $oauthAccessTokenValidationResponseTransfer,
            );
    }
}
