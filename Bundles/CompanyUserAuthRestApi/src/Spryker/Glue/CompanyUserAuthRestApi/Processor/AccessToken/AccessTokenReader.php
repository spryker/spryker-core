<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi\Processor\AccessToken;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\RestCompanyUserAccessTokensAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestTokenResponseAttributesTransfer;
use Spryker\Glue\CompanyUserAuthRestApi\CompanyUserAuthRestApiConfig;
use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthClientInterface;
use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthCompanyUserClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class AccessTokenReader implements AccessTokenReaderInterface
{
    /**
     * @var \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthClientInterface
     */
    protected $oauthClient;

    /**
     * @var \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthCompanyUserClientInterface
     */
    protected $oauthCompanyUserClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthClientInterface $oauthClient
     * @param \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthCompanyUserClientInterface $oauthCompanyUserClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        CompanyUserAuthRestApiToOauthClientInterface $oauthClient,
        CompanyUserAuthRestApiToOauthCompanyUserClientInterface $oauthCompanyUserClient,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->oauthClient = $oauthClient;
        $this->oauthCompanyUserClient = $oauthCompanyUserClient;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCompanyUserAccessTokensAttributesTransfer $restCompanyUserAccessTokensAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function processAccessTokenRequest(
        RestRequestInterface $restRequest,
        RestCompanyUserAccessTokensAttributesTransfer $restCompanyUserAccessTokensAttributesTransfer
    ): RestResponseInterface {
        $oauthRequestTransfer = $this->createOauthRequestTransfer($restRequest, $restCompanyUserAccessTokensAttributesTransfer);

        $oauthResponseTransfer = $this->oauthClient->processAccessTokenRequest($oauthRequestTransfer);

        if (!$oauthResponseTransfer->getIsValid()) {
            return $this->createInvalidLoginResponse();
        }

        return $this->createResponse($oauthResponseTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCompanyUserAccessTokensAttributesTransfer $restCompanyUserAccessTokensAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRequestTransfer
     */
    protected function createOauthRequestTransfer(
        RestRequestInterface $restRequest,
        RestCompanyUserAccessTokensAttributesTransfer $restCompanyUserAccessTokensAttributesTransfer
    ): OauthRequestTransfer {
        $oauthRequestTransfer = (new OauthRequestTransfer())
            ->setIdCompanyUser($restCompanyUserAccessTokensAttributesTransfer->getIdCompanyUser())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier())
            ->setGrantType(CompanyUserAuthRestApiConfig::CLIENT_GRANT_USER)
            ->setClientId($this->oauthCompanyUserClient->getClientId())
            ->setClientSecret($this->oauthCompanyUserClient->getClientSecret());

        return $oauthRequestTransfer;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createInvalidLoginResponse(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CompanyUserAuthRestApiConfig::RESPONSE_CODE_INVALID_LOGIN)
            ->setStatus(Response::HTTP_UNAUTHORIZED)
            ->setDetail(CompanyUserAuthRestApiConfig::RESPONSE_DETAIL_INVALID_LOGIN);

        $response = $this->restResourceBuilder->createRestResponse();
        $response->addError($restErrorTransfer);

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createResponse(OauthResponseTransfer $oauthResponseTransfer): RestResponseInterface
    {
        $restTokenAttributesTransfer = new RestTokenResponseAttributesTransfer();
        $restTokenAttributesTransfer->fromArray($oauthResponseTransfer->toArray(), true);

        $accessTokenResource = $this->restResourceBuilder
            ->createRestResource(
                CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS,
                null,
                $restTokenAttributesTransfer
            );

        $response = $this->restResourceBuilder->createRestResponse();
        $response->addResource($accessTokenResource);

        return $response;
    }
}
