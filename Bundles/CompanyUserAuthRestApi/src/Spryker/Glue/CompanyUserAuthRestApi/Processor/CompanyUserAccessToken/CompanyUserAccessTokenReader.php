<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi\Processor\CompanyUserAccessToken;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\RestCompanyUserAccessTokenResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCompanyUserAccessTokensAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CompanyUserAuthRestApi\CompanyUserAuthRestApiConfig;
use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CompanyUserAccessTokenReader implements CompanyUserAccessTokenReaderInterface
{
    /**
     * @var \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthClientInterface
     */
    protected $oauthClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthClientInterface $oauthClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        CompanyUserAuthRestApiToOauthClientInterface $oauthClient,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->oauthClient = $oauthClient;
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
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
            ->setGrantType(CompanyUserAuthRestApiConfig::CLIENT_GRANT_USER);

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

        return $this->restResourceBuilder->createRestResponse()
            ->addError($restErrorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createResponse(OauthResponseTransfer $oauthResponseTransfer): RestResponseInterface
    {
        $restTokenAttributesTransfer = new RestCompanyUserAccessTokenResponseAttributesTransfer();
        $restTokenAttributesTransfer->fromArray($oauthResponseTransfer->toArray(), true);

        $companyUserAccessTokenResource = $this->restResourceBuilder
            ->createRestResource(
                CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS,
                null,
                $restTokenAttributesTransfer
            );

        return $this->restResourceBuilder->createRestResponse()
            ->addResource($companyUserAccessTokenResource);
    }
}
