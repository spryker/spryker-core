<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Business\Expander;

use Generated\Shared\Transfer\AccessTokenRequestOptionsTransfer;
use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\HttpRequestTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;
use Spryker\Zed\OauthClient\Business\Exception\AccessTokenNotFoundException;
use Spryker\Zed\OauthClient\Business\Provider\OauthAccessTokenProviderInterface;
use Spryker\Zed\OauthClient\OauthClientConfig;

class RequestAuthorizationDataExpander implements RequestAuthorizationDataExpanderInterface
{
    /**
     * @var string
     */
    protected const HEADER_AUTHORIZATION = 'Authorization';

    /**
     * @var \Spryker\Zed\OauthClient\Business\Provider\OauthAccessTokenProviderInterface
     */
    protected $oauthAccessTokenProvider;

    /**
     * @var \Spryker\Zed\OauthClient\OauthClientConfig
     */
    protected $oauthClientConfig;

    /**
     * @param \Spryker\Zed\OauthClient\Business\Provider\OauthAccessTokenProviderInterface $oauthAccessTokenProvider
     * @param \Spryker\Zed\OauthClient\OauthClientConfig $oauthClientConfig
     */
    public function __construct(
        OauthAccessTokenProviderInterface $oauthAccessTokenProvider,
        OauthClientConfig $oauthClientConfig
    ) {
        $this->oauthAccessTokenProvider = $oauthAccessTokenProvider;
        $this->oauthClientConfig = $oauthClientConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer
     */
    public function expandPaymentAuthorizeRequest(
        PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
    ): PaymentAuthorizeRequestTransfer {
        $accessTokenRequestOptionsTransfer = (new AccessTokenRequestOptionsTransfer())
            ->setAudience($this->oauthClientConfig->getOauthOptionAudienceForPaymentAuthorize());

        $accessTokenRequestTransfer = (new AccessTokenRequestTransfer())
            ->setGrantType($this->oauthClientConfig->getOauthGrantTypeForPaymentAuthorize())
            ->setProviderName($this->oauthClientConfig->getOauthProviderNameForPaymentAuthorize())
            ->setAccessTokenRequestOptions($accessTokenRequestOptionsTransfer);

        $paymentAuthorizeRequestTransfer->setAuthorization($this->getAuthorizationValue($accessTokenRequestTransfer));

        return $paymentAuthorizeRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function expandMessageAttributes(
        MessageAttributesTransfer $messageAttributesTransfer
    ): MessageAttributesTransfer {
        $accessTokenRequestOptionsTransfer = (new AccessTokenRequestOptionsTransfer())
            ->setAudience($this->oauthClientConfig->getOauthOptionAudienceForMessageBroker());

        if (
            $this->oauthClientConfig->isAccessTokenRequestExpandedByMessageAttributes()
            && $messageAttributesTransfer->getStoreReference()
        ) {
            $accessTokenRequestOptionsTransfer->setStoreReference($messageAttributesTransfer->getStoreReference());
        }

        $accessTokenRequestTransfer = (new AccessTokenRequestTransfer())
            ->setGrantType($this->oauthClientConfig->getOauthGrantTypeForMessageBroker())
            ->setProviderName($this->oauthClientConfig->getOauthProviderNameForMessageBroker())
            ->setAccessTokenRequestOptions($accessTokenRequestOptionsTransfer);

        $accessTokenRequestTransfer->setAccessTokenRequestOptions($accessTokenRequestOptionsTransfer);

        $messageAttributesTransfer->setAuthorization($this->getAuthorizationValue($accessTokenRequestTransfer));

        return $messageAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HttpRequestTransfer
     */
    public function expandHttpChannelMessageReceiverRequest(HttpRequestTransfer $httpRequestTransfer): HttpRequestTransfer
    {
        $accessTokenRequestOptionsTransfer = (new AccessTokenRequestOptionsTransfer())
            ->setAudience($this->oauthClientConfig->getOauthOptionAudienceForMessageBroker());

        $accessTokenRequestTransfer = (new AccessTokenRequestTransfer())
            ->setGrantType($this->oauthClientConfig->getOauthGrantTypeForMessageBroker())
            ->setProviderName($this->oauthClientConfig->getOauthProviderNameForMessageBroker())
            ->setAccessTokenRequestOptions($accessTokenRequestOptionsTransfer);

        $httpRequestTransfer->addHeader(
            static::HEADER_AUTHORIZATION,
            $this->getAuthorizationValue($accessTokenRequestTransfer),
        );

        return $httpRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @throws \Spryker\Zed\OauthClient\Business\Exception\AccessTokenNotFoundException
     *
     * @return string
     */
    protected function getAuthorizationValue(AccessTokenRequestTransfer $accessTokenRequestTransfer): string
    {
        $accessTokenResponseTransfer = $this->oauthAccessTokenProvider->getAccessToken($accessTokenRequestTransfer);

        if (!$accessTokenResponseTransfer->getIsSuccessful()) {
            throw new AccessTokenNotFoundException(
                $accessTokenResponseTransfer->getAccessTokenErrorOrFail()->getErrorOrFail(),
            );
        }

        return sprintf('Bearer %s', $accessTokenResponseTransfer->getAccessToken());
    }
}
