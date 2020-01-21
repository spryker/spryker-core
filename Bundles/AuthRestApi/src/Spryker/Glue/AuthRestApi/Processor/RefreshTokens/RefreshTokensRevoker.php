<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\RefreshTokens;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer;
use Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToCustomerClientInterface;
use Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class RefreshTokensRevoker implements RefreshTokensRevokerInterface
{
    /**
     * @var \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface
     */
    protected $oauthClient;

    /**
     * @var \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface $oauthClient
     * @param \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        AuthRestApiToOauthClientInterface $oauthClient,
        AuthRestApiToCustomerClientInterface $customerClient,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->oauthClient = $oauthClient;
        $this->customerClient = $customerClient;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param string|null $refreshTokenIdentifier
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function revokeConcreteRefreshToken(?string $refreshTokenIdentifier, RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $revokeRefreshTokenRequestTransfer = new RevokeRefreshTokenRequestTransfer();

        $revokeRefreshTokenRequestTransfer->setRefreshToken($refreshTokenIdentifier);

        $revokeRefreshTokenResponseTransfer = $this->oauthClient->revokeConcreteRefreshToken($revokeRefreshTokenRequestTransfer);

        if (!$revokeRefreshTokenResponseTransfer->getIsSuccessful()) {
            return $restResponse->addError($this->createErrorRevokeRefreshTokenIsNotValid());
        }

        return $restResponse->setStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorRevokeRefreshTokenIsNotValid(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
//            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_RESTORE_PASSWORD_KEY_INVALID)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
//            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_RESTORE_PASSWORD_KEY_INVALID);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function revokeCurrentCustomerRefreshTokens(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $revokeRefreshTokenRequestTransfer = new RevokeRefreshTokenRequestTransfer();

        $restUserTransfer = $restRequest->getRestUser();

        $customer = $this->customerClient->getCustomerById($restUserTransfer->getSurrogateIdentifier());

        $revokeRefreshTokenRequestTransfer->setCustomer($customer);

        $revokeRefreshTokenResponseTransfer = $this->oauthClient->revokeRefreshTokensByCustomer($revokeRefreshTokenRequestTransfer);

        if (!$revokeRefreshTokenResponseTransfer->getIsSuccessful()) {
            return $restResponse->addError($this->createErrorRevokeRefreshTokenIsNotValid());
        }

        return $restResponse->setStatus(Response::HTTP_NO_CONTENT);
    }
}
