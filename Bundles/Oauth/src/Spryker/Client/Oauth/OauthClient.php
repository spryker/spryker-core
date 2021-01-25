<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Oauth\OauthFactory getFactory()
 */
class OauthClient extends AbstractClient implements OauthClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessTokenRequest(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        return $this->getFactory()->createOauthStub()->processAccessTokenRequest($oauthRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\Oauth\OauthClient::validateOauthAccessToken()} instead.
     *
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    public function validateAccessToken(
        OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
    ): OauthAccessTokenValidationResponseTransfer {
        return $this->getFactory()->createAccessTokenValidator()->validate($authAccessTokenValidationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    public function validateOauthAccessToken(
        OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
    ): OauthAccessTokenValidationResponseTransfer {
        return $this->getFactory()->createOauthAccessTokenValidator()->validate($authAccessTokenValidationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $refreshTokenIdentifier
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer
     */
    public function revokeRefreshToken(string $refreshTokenIdentifier, string $customerReference): RevokeRefreshTokenResponseTransfer
    {
        $revokeRefreshTokenRequestTransfer = (new RevokeRefreshTokenRequestTransfer())
            ->setCustomerReference($customerReference)
            ->setRefreshToken($refreshTokenIdentifier);

        return $this->getFactory()->createOauthStub()->revokeRefreshToken($revokeRefreshTokenRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer
     */
    public function revokeAllRefreshTokens(string $customerReference): RevokeRefreshTokenResponseTransfer
    {
        $revokeRefreshTokenRequestTransfer = (new RevokeRefreshTokenRequestTransfer())
            ->setCustomerReference($customerReference);

        return $this->getFactory()->createOauthStub()->revokeAllRefreshTokens($revokeRefreshTokenRequestTransfer);
    }
}
