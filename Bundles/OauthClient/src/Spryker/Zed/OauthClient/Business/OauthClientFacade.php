<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Business;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Generated\Shared\Transfer\HttpRequestTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\OauthClient\Business\OauthClientBusinessFactory getFactory()
 * @method \Spryker\Zed\OauthClient\Persistence\OauthClientEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\OauthClient\Persistence\OauthClientRepositoryInterface getRepository()
 */
class OauthClientFacade extends AbstractFacade implements OauthClientFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function getAccessToken(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenResponseTransfer
    {
        return $this->getFactory()->createOauthAccessTokenProvider()->getAccessToken($accessTokenRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function expandMessageAttributes(
        MessageAttributesTransfer $messageAttributesTransfer
    ): MessageAttributesTransfer {
        return $this->getFactory()
            ->createRequestAuthorizationDataExpander()
            ->expandMessageAttributes($messageAttributesTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer
     */
    public function expandPaymentAuthorizeRequest(
        PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
    ): PaymentAuthorizeRequestTransfer {
        return $this->getFactory()
            ->createRequestAuthorizationDataExpander()
            ->expandPaymentAuthorizeRequest($paymentAuthorizeRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HttpRequestTransfer
     */
    public function expandHttpChannelMessageReceiverRequest(HttpRequestTransfer $httpRequestTransfer): HttpRequestTransfer
    {
        return $this->getFactory()
            ->createRequestAuthorizationDataExpander()
            ->expandHttpChannelMessageReceiverRequest($httpRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expandAccessTokenRequestWithTenantIdentifier(
        AccessTokenRequestTransfer $accessTokenRequestTransfer
    ): AccessTokenRequestTransfer {
        return $this->getFactory()
            ->createAccessTokenRequestExpander()
            ->expandAccessTokenRequestWithTenantIdentifier($accessTokenRequestTransfer);
    }
}
