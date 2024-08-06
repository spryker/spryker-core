<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Business;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\HttpRequestTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;

interface OauthClientFacadeInterface
{
    /**
     * Specification:
     * - Retrieves an access token from an access token provider by AccessTokenRequestTransfer.
     * - Uses `AccessTokenRequestExpanderPluginInterface` plugins stack to expand AccessTokenRequestTransfer.
     * - Uses `OauthAccessTokenProviderPluginInterface` plugins stack to retrieve an access token.
     * - Caches retrieved access token.
     * - Ignores cache if `AccessTokenRequestTransfer::ignoreCache = true`.
     * - Returns `AccessTokenResponseTransfer::isSuccessful = true` in case of successful token retrieval.
     * - Returns `AccessTokenResponseTransfer::isSuccessful = false` in case of failure.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function getAccessToken(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenResponseTransfer;

    /**
     * Specification:
     * - Retrieves an access token from an access token provider by AccessTokenRequestTransfer.
     * - Throws exception `AccessTokenNotFoundException` in case if `AccessTokenResponseTransfer::isSuccessful = false`.
     * - Populates `AccessTokenRequestOptions.storeReference` with `MessageAttributes.storeReference` if `OauthClientConfig::isAccessTokenRequestExpandedByMessageAttributes()` returns `true`.
     * - Updates the `MessageAttributes.authorization` property with the received access token.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function expandMessageAttributes(MessageAttributesTransfer $messageAttributesTransfer): MessageAttributesTransfer;

    /**
     * Specification:
     * - Retrieves an access token from an access token provider by AccessTokenRequestTransfer.
     * - Throws exception `AccessTokenNotFoundException` in case if `AccessTokenResponseTransfer::isSuccessful = false`.
     * - Adds the authorization header to the `AcpHttpRequestTransfer`.
     * - Returns the `AcpHttpRequestTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpRequestTransfer
     */
    public function expandRequest(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpRequestTransfer;

    /**
     * Specification:
     * - Retrieves an access token from an access token provider by AccessTokenRequestTransfer.
     * - Throws exception `AccessTokenNotFoundException` in case if `AccessTokenResponseTransfer::isSuccessful = false`.
     * - Updates the `PaymentAuthorizeRequest.authorization` property with the received access token.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer
     */
    public function expandPaymentAuthorizeRequest(
        PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
    ): PaymentAuthorizeRequestTransfer;

    /**
     * Specification:
     * - Retrieves an access token from an access token provider with OAuth credentials.
     * - Updates the `HttpRequestTransfer.authorization` property with the received access token.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HttpRequestTransfer
     */
    public function expandHttpChannelMessageReceiverRequest(HttpRequestTransfer $httpRequestTransfer): HttpRequestTransfer;

    /**
     * Specification:
     * - Locates a tenant identifier if it has been provided.
     * - Expands `AccessTokenRequest.accessTokenRequestOptions` by including the located tenant identifier.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expandAccessTokenRequestWithTenantIdentifier(
        AccessTokenRequestTransfer $accessTokenRequestTransfer
    ): AccessTokenRequestTransfer;
}
